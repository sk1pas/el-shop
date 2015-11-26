$(document).ready(function() {
    
$('.delete').click(function() {
    
    var rel = $(this).attr("rel");
    
    $.confirm({
        'title' : 'Подтверждение удаления',
        'message' : 'После удаления восстановление будет невозможным. Продолжить?',
        'buttons' : {
            'Да' : {
                'class' : 'blue',
                'action' : function() {
                    location.href = rel;
                }
            },
            'Нет' : {
                'class' : 'gray',
                'action' : function (){}
            }
        }
    });
});

$('#select-links').click(function() {
    $("#list-links").slideToggle(200);
});

});