$(':checkbox:checked').prop('checked', false);

$('#australia').vectorMap({
    map: 'au_mill',
    backgroundColor: 'transparent',
    regionStyle: {
        initial: {
            fill: '#4976a0'
        }
    }
});

$('#map').vectorMap({
    map: 'map',
    backgroundColor: 'transparent',
    regionStyle: {
        initial: {
            fill: '#4976a0'
        }
    }
});
       
        