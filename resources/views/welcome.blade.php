<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

        <title>Point Of Sales - Cashier</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
        </style>
        <style>
            body {
                font-weight: bold;
                font-family: 'Nunito', sans-serif;
            }

            .total-amount-paid {
                border:1px solid !important;
                background-image:auto !important;
                background-color:auto !important;
                -webkit-box-shadow: auto !important;
                -moz-box-shadow: auto !important;
                box-shadow: auto !important;
                max-width:100% !important;
                text-align:center !important;
                pointer-events: auto !important;
            }

            input {
                border:none;
                background-image:none;
                background-color:transparent;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                max-width:100%;
                text-align:center;
                pointer-events: none;
            }

            .margin-quantity input {
                margin-top:-24px;
            }

            .bi-dash-circle {
                cursor: pointer;
            }

            .bi-plus-circle {
                cursor: pointer;
            }

            .quantity-section {
                padding-left:10px;
                padding-right:30px;
            }
        </style>
    </head>
    <body class="antialiased pt-5">
        <form action="{!! route('checkout') !!}"method="POST">
        @csrf
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
            <div class="container" style="border:1px solid black">
                <!-- Check Out Modal -->
                <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="d-flex justify-content-between">
                                    <div>Total Paid Amount</div>
                                    <div>RM<span class="checkout-total-amount-paid"></span></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>Total</div>
                                    <div>RM<span class="checkout-total"></span></div>
                                </div>
                                <div class="d-flex justify-content-between pt-2 pb-2">
                                    <div>Payment Method</div>
                                    <div>
                                        <select class="form-select" name="payment_method" aria-label="Default select example">
                                            <option value="cash" selected>Cash</option>
                                            <option value="card">Card</option>
                                          </select>
                                    </div>
                                    <input type="number" name="number-of-forms" class="number-of-forms" hidden>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <div>Change</div>
                                    <div>RM<input class="checkout-change" name="checkout-change" style="width:90px;"/></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Modal -->
                <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="d-flex justify-content-between">
                                    <div style="color:red;">Data submitted is incorrect, please do check one of the following below:<br>
                                        - Empty Order<br>
                                        - Total amount paid is empty<br>
                                        - Total amount paid is lesser than the order total price
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                <div class="col-md" style="text-align:center;">
                    <div class="col-12 pb-3">
                        POS<br>
                        Cashier
                    </div>
                    <div class="row pb-2">
                        <div class="col-3">
                            Product
                        </div>
                        <div class="col-3">
                            Price(RM)
                        </div>
                        <div class="col-3">
                            Quantity
                        </div>
                        <div class="col-3">
                            Cost(RM)
                        </div>
                    </div>
                    <div class="row product_row">
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <div>Subtotal</div>
                        <div>RM<input text="number" name="sub_total" class="sub-total" value="0.00" style="width:60px;"/></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>No. of items</div>
                        <div><input type="number" name="number_of_items" class="number-of-items" value="0" style="width:60px;"/></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Tax</div>
                        <div class="pr-4">6%</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Service Charge</div>
                        <div> - </div>
                    </div>

                    <hr >

                    <div class="d-flex justify-content-between">
                        <div>Total</div>
                        <div>RM<input text="number" name="total" class="total" value="0.00" style="width:60px;"/></div>
                    </div>

                    <div class="d-flex justify-content-center pt-5">
                        <div>Total Amount Paid (RM): <input type="number" class="total-amount-paid" name="total_amount_paid" step="any"></div>
                    </div>

                    <div class="d-flex row pt-5 pb-5">
                        <div class="col-6">
                            <button type="button" style="height:40px; width:150px; background-color: white; border-radius:50px;">Cancel</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn-checkout" style="height:40px; width:150px; background-color: white; border-radius:50px;">Check Out</button>
                        </div>
                    </div>

                </div>
                <div class="col-md" style="text-align:center; border-left:1px solid black;">
                    <div class="col-12 pb-5">
                        <b>Products</b>
                    </div>
                    <div class="row">
                    @foreach($products as $product)
                        <div class="col-6 pt-0 pb-5">
                            <div class="product-section" style="padding: 20px; border:1px solid black; cursor: pointer;">
                                <input type="text" class="product-name" name="product-name" value="{{ $product->name}}">
                                <input type="number" hidden class="product-price" name="product-price" value="{{ $product->price }}">
                            </div>
                        </div>
                        </button>
                    @endforeach
                    </div>
                </div>
                </div>
            </div>
        </form>
    </body>

<script>
    $('.btn-checkout').click(function(){
        if (!$('.total-amount-paid').val() || +$('.margin-quantity').length == 0 || Number($('.total').val()) > Number($('.total-amount-paid').val())){
            $('#errorModal').modal('show');
        } else {
            $('#checkoutModal').modal('show');
            $('.checkout-total-amount-paid').text(parseFloat($('.total-amount-paid').val()).toFixed(2));
            $('.checkout-total').text(parseFloat($('.total').val()).toFixed(2));
            $('.checkout-change').val(parseFloat($('.total-amount-paid').val() - $('.total').val()).toFixed(2));
            $('.number-of-forms').val(+$('.margin-quantity').length);
        }
    });

    $( ".product-section" ).click(function() {
        product_name = $(this).find('.product-name').val();
        product_price = $(this).find('.product-price').val();

        check_current_row_exist = $('.product_row').find(".product_"+product_name+"_name").val();

        if(check_current_row_exist){
            total_quantity = +($(".product_"+product_name+"_quantity").val());
            total_quantity += 1;
            $(".product_"+product_name+"_quantity").val(total_quantity);

            total_cost = +($(".product_"+product_name+"_cost").val());
            total_cost += +(product_price);
            $(".product_"+product_name+"_cost").val(total_cost);

            //sub total calculation
            sub_total = +$('.sub-total').val();
            sub_total += +product_price;
            $('.sub-total').val(sub_total.toFixed(2));

            //number of items calculation
            number_of_items = +$('.number-of-items').val();
            number_of_items += 1;
            $('.number-of-items').val(number_of_items);

            //total calculation
            total = sub_total + (sub_total * 0.06);
            $('.total').val(total.toFixed(2));
        } else {
            $("<div class='col-3 '>" +
            "<input type='text' class='" + "product_" + product_name + "_name"  + "' name=" + "product_" + product_name + "_name"  + " value='" + product_name + "'/><br/><br/>" +
            "</div>").appendTo('.product_row');
            $("<div class='col-3 product_" + product_price + "_price'>" +
                "<input type='text' class='" + "product_" + product_name + "_price"  + "' name=" + "product_" + product_name + "_price"  + " value='" + product_price + "'/><br/><br/>" +
            "</div>").appendTo('.product_row');
            $("<div class='col-3 margin-quantity'>" +
                "<div class='d-flex quantity-section'>" +
                    "<div class='minus_section'>" +
                        "<i class='bi bi-dash-circle " + "product_" + product_name + "_minus'" + "></i>" +
                        "<input type='hidden' value='product_" + product_name + "_price'/>" +
                    "</div>" +
                "<input type='text' class='" + "product_" + product_name + "_quantity"  + "' name=" + "product_" + product_name + "_quantity"  + " value='" + 1 + "'/><br/><br/>" +
                    "<div class='add_section'>" +
                        "<i class='bi bi-plus-circle " + "product_" + product_name + "_add'" + "></i>" +
                        "<input type='hidden' value='product_" + product_name + "_price'/>" +
                    "</div>" +
                "</div>" +
            "</div>").appendTo('.product_row');
            $("<div class='col-3 cost_section'>" +
                "<input type='text' class='" + "product_" + product_name + "_cost"  + "' name=" + "product_" + product_name + "_cost"  + " value='" + product_price + "'/><br/><br/>" +
            "</div>").appendTo('.product_row');

            //sub total calculation
            sub_total = +$('.sub-total').val();
            sub_total += +$('.product_'+product_name+'_cost').val();
            $('.sub-total').val(sub_total.toFixed(2));

            //number of items calculation
            number_of_items = +$('.number-of-items').val();
            number_of_items += 1;
            $('.number-of-items').val(number_of_items);

            //total calculation
            total = sub_total + (sub_total * 0.06);
            $('.total').val(total.toFixed(2));
        }
    });



    $('.product_row').on('click', '.minus_section', function() {
        current_row_quantity = $(this).siblings('input').val();
        current_row_product_price = $(this).parent().parent().next().find('input').val();
        current_row_cost = $('.'+$(this).find('input').val()).val();

        //Proceed if quantity is not 0
        if (current_row_quantity > 0){
            current_row_quantity -= 1;
            current_row_product_price -= current_row_cost;


            //sub total calculation
            sub_total = +$('.sub-total').val();
            sub_total -= current_row_cost;
            $('.sub-total').val(sub_total.toFixed(2));

            //number of items calculation
            number_of_items = +$('.number-of-items').val();
            number_of_items -= 1;
            $('.number-of-items').val(number_of_items);

            //total calculation
            total = sub_total + (sub_total * 0.06);
            $('.total').val(total.toFixed(2));
        }

        //Minus Current Row Quantity
        $(this).siblings('input').val(current_row_quantity);

        //Minus Current Row Cost
        $(this).parent().parent().next().find('input').val(current_row_product_price);
    });

    $('.product_row').on('click', '.add_section', function() {
        current_row_quantity = +($(this).siblings('input').val());
        current_row_product_price = +($(this).parent().parent().next().find('input').val());
        current_row_cost = +($('.'+$(this).find('input').val()).val());

        //Proceed if quantity is not 0
        if (current_row_quantity >=  0){
            current_row_quantity += 1;
            current_row_product_price += current_row_cost;

            //sub total calculation
            sub_total = +$('.sub-total').val();
            sub_total += current_row_cost;
            $('.sub-total').val(sub_total.toFixed(2));

            //number of items calculation
            number_of_items = +$('.number-of-items').val();
            number_of_items += 1;
            $('.number-of-items').val(number_of_items);

            //total calculation
            total = sub_total + (sub_total * 0.06);
            $('.total').val(total.toFixed(2));
        }

        //Add Current Row Quantity
        $(this).siblings('input').val(current_row_quantity);

        //Add Current Row Cost
        $(this).parent().parent().next().find('input').val(current_row_product_price);
    });
</script>
</html>
