LDAP Events
================

There are several LDAP related events to hook into. The events themselves are integrated into [LdapTools](https://github.com/ldaptools/ldaptools).
You can easily access them with this bundle by tagging your services to act on specific LDAP events. The recommended way
of doing this is creating a subscriber class, then creating a tagged service using that class. But overall there are
three main methods to using events: Service Event Subscribers, Service Event Listeners, or defining them using the event
dispatcher associated with the LdapManager.
 
* [Service Event Subscribers](#the-service-event-subscriber-method)
* [Service Event Listeners](#the-service-event-listener-method)
* [Event Dispatcher Method](#the-ldapmanager-event-dispatcher-method)
* [Injecting the LdapManager](#injecting-the-ldapmanager)

For additional information of events please see the [LdapTools documentation](https://github.com/ldaptools/ldaptools/blob/master/docs/en/reference/Events.md).

## The Service Event Subscriber Method

Using this method you can define all your event actions in a single class using different methods. Then define a service
that uses that class along with a special tag.

1. Create the subscriber class:

```php
namespace AppBundle\Subscriber;

use LdapTools\Event\EventSubscriberInterface;
use LdapTools\Event\Event;
use LdapTools\Event\LdapObjectEvent;
use LdapTools\Event\LdapObjectCreationEvent;

class LdapToolsSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        /**
          * This should return an array mapping where the key is a LdapTools event name and the value is the method name
          * in this class that should be called for the event. All event names are defined as constants within the 
          * '\LdapTools\Event\Event' class.
          */
        return [
            Event::LDAP_OBJECT_BEFORE_MODIFY => 'beforeModify',
            Event::LDAP_OBJECT_AFTER_CREATE > 'afterCreate',
        ];
    }

    /**
     * Will be called before a LDAP object modification is saved to LDAP.
     */
    public function beforeModify(LdapObjectEvent $event)
    {
        $ldapObject = $event->getLdapObject();

        // Perform some custom logic against the LDAP object that's about to be modified...
    }
    
    /**
     * Will be called after an LDAP object is created.
     */
    public function afterCreate(LdapObjectCreationEvent $event)
    {
        $attributes = $event->getData();
        $dn = $event->getDn();
        
        // Perform some custom logic regarding the LDAP object that was created...
    }
}
```

2. Define the class as a service in your service config file and tag it:

**
```xml
<?xml version="1.0" ?>
    <!-- In your bundles services config file -->
    <services>
        <!-- ... -->
        
        <service id="app.ldap_events" class="AppBundle\Subscriber\LdapToolsSubscriber" >
            <tag name="ldap_tools.event_subscriber"/>
        </service>
        
        <!-- ... -->
    </services>
```

## The Service Event Listener Method

Using this method you can define your event action in a class, then you create a service that uses that class and define
the event name and method it should call within the service definition.
 
1. Add the method to a class:

```php
namespace AppBundle\Misc;

use LdapTools\Event\LdapObjectEvent;

class AppUtility
{
    #...
    
    /**
     * Will be called after a LDAP objects modifications are saved to LDAP.
     */
    public function afterModify(LdapObjectEvent $event)
    {
        $ldapObject = $event->getLdapObject();

        // Perform some custom logic against the LDAP object that was modified...
    }
    
    # ...
}
```

2. Define the class as a service in your service config file and tag it:

*In your bundles services config file*
```xml
<?xml version="1.0" ?>
    <!-- ... -->
    <services>
        <!-- ... -->
        
        <service id="app.ldap_event" class="AppBundle\Misc\AppUtility" >
            <tag name="ldap_tools.event_listener" event="ldap.object.after_modify" method="afterModify"/>
        </service>
        
        <!-- ... -->
    </services>
```

The difference above in this method is that you directly define the event name and class method name directly on the
service definition. Additionally, you tag it with `ldap_tools.event_listener`.

## The LdapManager Event Dispatcher Method

It's also possible to define an event action by directly using the event dispatcher of the LdapManager:

```php
use LdapTools\Event\Event;
use LdapTools\Event\LdapObjectEvent;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $dispatcher = $this->get('ldap_tools.ldap_manager')->getEventDispatcher();
        
        $dispatcher->addListener(Event::LDAP_OBJECT_BEFORE_MODIFY, function(LdapObjectEvent $event) {
             if ($event->getLdapObject()->hasFirstName('foo')) {
                 $event->getLdapObject()->setFirstName('bar');
             }
         });
        
        # ...
    }
}
```

## Injecting the LdapManager

Often times you might want to inject the `ldap_tools.ldap_manager` service into one of your event definitions you have
tagged as a service. However, by doing this you will create a circular reference error in the Dependency Injection Container.
To get around this you can [mark the service as lazy](http://symfony.com/doc/current/service_container/lazy_services.html) in your event service definition:

1. Install the needed dependency:

```bash
composer require ocramius/proxy-manager
```

2. Update your service definition:

```yaml
services:
    app_bundle.event.ldap_listener:
        class: AppBundle\Event\LdapEventListener
        # This needs to be set to true...which will delay resolution/instantiation of the service...
        lazy: true
        # Inject the LdapManager in your constructor...
        arguments: ['@ldap_tools.ldap_manager']
        # Tag the listener so it gets loaded...
        tags:
            - { name: 'ldap_tools.event_listener', event: 'ldap.operation.execute.before', method: beforeOperation }
```

3. Clear and Warm-up the cache:

```bash
php bin/console cache:clear --env=dev
php bin/console cache:warmup --env=dev
```

Now when you use the service definition with the LdapManager it should no longer result in a circular reference error in
the DiC.