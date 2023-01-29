$(function() {


    const items1 = $('#items');
    const quanitiy1 = $('#quantity');
    const addItem = $('#add-item');
    const table = $('#transaction');
    const totalSalesElement = $('#total-sales');
    const baseUrl = "http://pos.local";
    let totalSales = 0;


    $.ajax({
        type: "GET",
        url: baseUrl + "/transactions",
        success: function(response) {
            response.body.forEach(element => {
                table.append(`
                    <tr data-id=${element.id}>
                        <td class="text-center tranaction-id">${element.id}</td>
                        <td class="text-center item-id">${element.items_id}</td>
                        <td class="text-center">${element.item_name}</td>
                        <td class="text-center">${element.quantity}</td>
                        <td class="text-center unit-price">${element.price}</td>
                        <td class="text-center">${element.total}</td>
                    </tr>
                 `);

                totalSales += element.quantity * element.price;

                totalSalesElement.text(totalSales + "JOD");
            });
        }
    });


    $('#items_id').focus();

    addItem.click(function() {



        let user_id_input = $('#user_id').val();
        let items_id_input = $('#items_id').val();
        let quantity_input = $('#quantity').val();
        if (user_id_input == "" || items_id_input == "" || quantity_input == "") {
            alert('You need to enter a requied value to proceed!');
        }

        $.ajax({
            type: "POST",
            url: baseUrl + "/transactions/create",
            data: JSON.stringify({
                user_id: user_id_input,
                items_id: items_id_input,
                quantity: quantity_input
            }),
            dataType: "application/json",
            success: function(response) {
                response.body.forEach(element => {
                    table.append(`
                    <tr data-id=${element.id}>
                        <input type="hidden" name='id'data-id=id${element.id} value=${element.id}>
                        <td class="text-center tranaction-id item-id">${element.id}</td>
                        <td class="text-center">${element.items_id}</td>
                        <td class="text-center">${element.item_name}</td>
                        <input type="hidden" name='pre-quantity' data-id=pre-quantity${element.id} value=${element.quantity}>
                        <td class="text-center"><input  type="number" data-id=input${element.id} name=input${element.id} value=${element.quantity} style="
                        background-color: white;
                        width: 5rem;
                        border: none;
                        background-color: white;" class="text-center"></td>
                        <td name='price_id'data-id=price${element.id} class="text-center unit-price">${element.price}</td>
                        <input type="hidden" name='price'data-id=price${element.id} value=${element.price}>
                        <td data-id=total${element.id} class="text-center">${element.total}</td>
                        <td class="text-center">
                        <button type="submit" data-id=edit${element.id} class="text-center btn btn-warning edit "><i class="fa-solid fa-pen-to-square "></i></button>
                        <button data-id=delete${element.id} class="text-center btn btn-danger edit " type="button"><i class="fa-solid fa-trash "></i></button>
                        </td>
                        </tr>
                    `);
                    totalSales += element.quantity * element.price;

                    totalSalesElement.text(totalSales + "JOD");

                    let previous_quantity = $(`input[data-id="pre-quantity${element.id}"]`).val();

                    $(`button[data-id="edit${element.id}"]`).click(function() {
                        let newvalue = $(`input[data-id="input${element.id}"]`).val();
                        let price = $(`input[data-id="price${element.id}"]`).val();
                        let specificid = $(`input[data-id="id${element.id}"]`).val();
                        let stock = $(`input[data-id="stock${element.id}"]`).val();
                        let newtotal = $(`td[data-id="total${element.id}"]`);
                        newtotal.text(newvalue * price);

                        if (newvalue >= previous_quantity) {
                            totalSales += (newvalue - (previous_quantity)) * element.price;
                            totalSalesElement.text(totalSales + "JOD");
                            previous_quantity = newvalue;

                        } else if (newvalue < previous_quantity) {
                            totalSales -= (((previous_quantity) - newvalue) * element.price);
                            totalSalesElement.text(totalSales + "JOD");
                            previous_quantity = newvalue;
                        }


                        $.ajax({
                            type: "PUT",
                            url: baseUrl + "/transactions/update",
                            data: JSON.stringify({
                                id: specificid,
                                quantity: newvalue
                            }),
                            dataType: "application/json",
                            success: function(response) {



                            }

                        });


                    });


                    $(`button[data-id="delete${element.id}"]`).click(function() {

                        $.ajax({
                            type: "DELETE",
                            url: baseUrl + "/transactions/delete",
                            data: JSON.stringify({
                                id: element.id
                            }),
                            dataType: "application/json",
                            success: function(response) {

                            }
                        });
                        $(this).parent().hide('slow', function() {
                            $(this).parent().remove();
                        });
                        totalSales -= element.quantity * element.price;

                        totalSalesElement.text(totalSales + "JOD");
                    });
                });
            }
        });
    });


});