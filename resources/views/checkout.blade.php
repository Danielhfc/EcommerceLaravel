@extends('layouts.front')


@section('content')

    <div class="container">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dados para pagamento</h2>
                </div>
            </div>
        </div>
        <form action="" method="post">
            <div class="col-md-6">
            <div class="row">
                    <div class="col-md-12 form-group">
                        <label >Nome no Cartão </label>
                        <input type="text" class="form-control" name="card_name">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label >Número do Cartão <span class="brand"></span></label>
                        <input type="text" class="form-control" name="card_number">
                        <input type="hidden" name="card_brand">
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label >Mês de expiração</label>
                        <input type="text" class="form-control" name="card_month">
                    </div>
                

                    <div class="col-md-4 form-group">
                        <label >Ano de expiração</label>
                        <input type="text" class="form-control" name="card_year">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label >Código de segurança</label>
                        <input type="text" class="form-control" name="card_cvv">
                    </div>

                    <div class="col-md-12 installments form-group"></div>
                </div>
    
                <button class="btn btn-success btn-lg proccessCheckout">Efetuar Pagamento</button>

            </div>
        </form>
    </div>


    <script type="text/javascript" src=
"https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

<script src="{{asset('assets/js/jquery.ajax.js')}}"></script>
<script>
        const sessionId = '{{session()->get('pagseguro_session_code')}}';
        PagSeguroDirectPayment.setSessionId(sessionId);
    </script>

<script>
    let getHash = PagSeguroDirectPayment.onSenderHashReady(function(response){
                        if(response.status == 'error') {
                            console.log("caiu no erro do onSenderHashReady" + response.message);
                            return false;
                        }
                        var hash = response.senderHash; //Hash estará disponível nesta variável.
                    });

function proccessPayment(token, buttonTarget)
{
    let data = {
        card_token: token,
        hash: getHash,
        installment: document.querySelector('.select_installments').value,
        card_name: document.querySelector('input[name=card_name]').value,
        _token: '{{csrf_token()}}}'
    };

    $.ajax({
        type: 'POST',
        url: '{{route("checkout.proccess")}}',
        data: data,
        dataType: 'json',
        success: function(res){
            console.log(res);
        }
    });
}


function getInstallments(amount, brand) {
    PagSeguroDirectPayment.getInstallments({
        amount: amount,
        brand: brand,
        maxInstallmentNoInterest: 0,
        success: function(res) {
            let selectInstallments = drawSelectInstallments(res.installments[brand]);
            document.querySelector('div.installments').innerHTML = selectInstallments;
        },
        error: function(err) {
            console.log(err);
        },
        complete: function(res) {

        },
})
}

function drawSelectInstallments(installments) {
let select = '<label>Opções de Parcelamento:</label>';

select += '<select class="form-control select_installments">';

for(let l of installments) {
    select += `<option value="${l.quantity}|${l.installmentAmount}">${l.quantity}x de ${l.installmentAmount} - Total fica ${l.totalAmount}</option>`;
}


select += '</select>';

return select;
}
</script>

    <script>
        let amountTransaction = '{{$cartItems}}';
        let cardNumber = document.querySelector('input[name=card_number]');
        let spanBrand = document.querySelector('span.brand');
        

        cardNumber.addEventListener('keyup', function(){
            if(cardNumber.value.length >= 6){
                PagSeguroDirectPayment.getBrand({
                    cardBin: cardNumber.value.substr(0, 6),
                    success: function(res){                    
                        let imgFlag = `<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/${res.brand.name}.png">`;
                        spanBrand.innerHTML = imgFlag;
                        document.querySelector('input[name=card_brand').value = res.brand.name
                        getInstallments(amountTransaction, res.brand.name);
                    },
                    error: function(err){
                        console.log(err);                                             
                    },
                    complete: function(res){
                        //console.log('Complete: ' + res);
                    }
                });
            }
        });

        let submitButton = document.querySelector('button.proccessCheckout');

            submitButton.addEventListener('click', function(event){
                event.preventDefault();


                PagSeguroDirectPayment.createCardToken({
                    cardNumber: document.querySelector('input[name=card_number]').value,
                    brand:      document.querySelector('input[name=card_brand]').value,
                    cvv:        document.querySelector('input[name=card_cvv]').value,
                    expirationMonth: document.querySelector('input[name=card_month]').value,
                    expirat
                    ionYear:  document.querySelector('input[name=card_year]').value,
                    success: function(res) {
                        console.log(res);
                        proccessPayment(res.card.token);
                    },

                });
            });
    </script>
@endsection