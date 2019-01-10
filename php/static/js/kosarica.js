function dodajVKosarico(id, kol, swalTitle, swalText) {
    $.post( BASE_URL+"api/kosarica", { id: id, kol: kol })
        .done(function( data ) {
            console.log(data);
            $('.swal-title').html(swalTitle);
            $('.swal-text').html(swalText);
        });
}

function posodobiKosarico() {
    var kosarica = $('.header-cart');
    var seznam = kosarica.find('.header-cart-wrapitem');
    var znesek = kosarica.find('.header-cart-total');
    seznam.html('');
    znesek.html('');

    $.get( BASE_URL+"api/kosarica", function( data ) {
        var vsota = 0;
        $(data).each(function() {
            this.cena = parseFloat(this.cena).toFixed(2);
            vsota += this.kol*this.cena;
            seznam.append(
                `<li class="header-cart-item">
                    <div class="header-cart-item-img">
                        <img src="`+BASE_URL+`piva/`+this.id+`.jpg" alt="IMG">
                    </div>
        
                    <div class="header-cart-item-txt">
                        <a href="`+BASE_URL+`piva/`+this.id+`" class="header-cart-item-name">
                            `+this.imeZnamke+`<br><b>`+this.naziv+`</b>
                        </a>
        
                        <span class="header-cart-item-info">
                            `+this.kol+` x `+String(this.cena).replace(".", ",")+` €
                        </span>
                    </div>
                </li>`)
        });
        znesek.html('Skupaj: <b>'+String(vsota.toFixed(2)).replace(".", ",")+' € </b>');
    });
}