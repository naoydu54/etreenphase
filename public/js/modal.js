function modal(selecteur, type,  title, text, otherData, buttonRigth='Confirmer', buttonLeft='Annuler'){


    $('.content-header').after('<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title" id="exampleModalLabel">'+title+'</h5>' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>' +
        '<div class="modal-body">' +
        text +
        '</div>' +
        '<div class="modal-footer">' +
        ' <button type="button" class="btn btn-secondary" data-dismiss="modal">'+buttonLeft+'</button>' +
        '<button type="button" class="btn btn-primary confirmation">'+buttonRigth+'</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>')

    if(type === 'modal-danger'){
        $('.modal-header').css('background-color', '#dc3545')
    }else if(type === 'modal-success'){
        $('.modal-header').css('background-color', '#218838')
    }
    else if(type === 'modal-primary'){
        $('.modal-header').css('background-color', '#007bff')
    }
    $('#myModal').modal('show')
}