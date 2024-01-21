let url = window.location.href;
function validateNewsletter(){
    let email = $('.emailSignUp').val();
    let mailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    if(email == ""){
        $('#newsError').html("");
        return false;
    }
    if (!mailRegex.test(email)) {
        $('#newsError').html("Email is not in correct format. Example: jhondoe@gmail.com");
        return false;
    } else {
        $('#newsError').html("");
        return true;
    }
    }
$('.emailSignUp').on('blur', function(){
    validateNewsletter();
});
$('.news-signUp').on('click', function(e){
    e.preventDefault();
    if($('.emailSignUp').val() == "") {
        $('#newsError').html("Email cannot be empty field!");
        return false;
        }
    if(validateNewsletter()){

        $.ajax({
                 url: "models/insertNewsletter.php",
                 method: "post",
                 data: {
                     email: $('.emailSignUp').val(),
                 },
                 success: function (response) {
                     $('#newsError').css({'color': 'white'});
                     $('#newsError').html(response);
                     setTimeout(function(){
                            $('#newsError').html("");
                     }, 3000);
                 },
                 error: function (xhr, status, error) {
                     $('#newsError').css({'color': '#ed3e3e'});
                     $('#newsError').html(xhr.responseText);
                 }
        });
    }
    else{
        return false;
    }
});

var e = jQuery.Event("keyup", { keyCode: 20 });
$('#search').on('keyup', function(){
    let search = $(this).val();
    if($(this).val() == "") {
        $('#searchedProducts').html("");
        $('#searchedProducts').hide();
    }
    if(search.length > 2){
        $.ajax({
            url: "models/search.php",
            method: "POST",
            data: {
                search: search
            },
            success: function(response){
                $('#searchedProducts').show();
                $('#searchedProducts').html(response);
            },
            error: function(xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
});
$('#searchedProducts').hide();
$('#search').on('blur', function(){
    if($(this).val() == "") {
        $('#searchedProducts').html("");
        $('#searchedProducts').hide();
    }
});
function removeEmptyDiscount(){
    $('.discount-perc').each(function(){
        if($(this).html() == ""){
            $(this).hide();
        }
    });
}
function removeEmptyPrice(){
    $('.oldPrice del').each(function(){
        if($(this).html() == ""){
            $(this).parent().remove();
        }
    });
}
function getClickedFilters(className){
    let array = [];
    $('.'+ className +':checked').each(function(){
        array.push($(this).data('id'));
    });
    return array;
}
function sendPage(){
    $('.pagination li').on('click', function(){
        $('.pagination li').each(function(){
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        getFilteredProducts();
    });
}
function addToWishlist(){
    $('.addToWish').on('click', function(){
        let id = $(this).data('id');
        let wishNum = parseInt($('.numberProductsWish').html());
        console.log(wishNum,typeof wishNum);
        $.ajax({
            url: "models/addToWishlist.php",
            method: "POST",
            data: {
                id: id
            },
            success: function (response) {
                $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
                $('.modal-body').html(response);
                $('#exampleModal').modal('show');
                $('.numberProductsWish').html(wishNum + 1);
                setTimeout(function () {
                    $('.modal-body').html("");
                    $('#exampleModal').modal('hide');
                }, 2500);
            },
            error: function (xhr, status, error) {
                $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                $('.modal-body').html(xhr.responseText);
                $('#exampleModal').modal('show');
                setTimeout(function () {
                    $('.modal-body').html("");
                    $('#exampleModal').modal('hide');
                }, 2500);
            }
        });
    });
}
function markActivePage(page){
    let navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(navlink => {
        if(navlink.innerHTML == page)
            navlink.classList.add('active');
    });
}
function calculateStars(number){
    let html = '';
    for(let i = 1; i <= number; i++){
        html += `<i class="fas fa-star"></i>`;
    }
    for(let i = 1; i <= 5 - number; i++){
        html += `<i class="far fa-star"></i>`;
    }
    return html;
}
function productTotalPrice(quantity, price){
    return quantity * price;
}
function changeProductQuantity(){
    let pluses = document.querySelectorAll('.btn-plus');
    let minuses = document.querySelectorAll('.btn-minus');
    pluses.forEach(plus => plus.addEventListener('click', function(){
        $('.loader-wrapper').css('display', 'flex');
        let id = plus.getAttribute("data-id");
        setTimeout(function(){
        let productPriceHTML = document.querySelector(`.ProductPrice[name='pr${id}']`).innerHTML;
        let productPrice = productPriceHTML.slice(1);
        let quanNumber = Number(plus.parentElement.parentElement.children[1].value);
        let totalProductPrice = document.querySelector(`.totalProductPrice[name='pr${id}']`);
        totalProductPrice.innerHTML = `$${productTotalPrice(quanNumber + 1, productPrice)}`;
        plus.parentElement.parentElement.children[1].value = quanNumber + 1;
        updateCart(id, quanNumber + 1);
        totalPrice();
        $('.loader-wrapper').css('display', 'none');
        }, 1000);
    }));
    minuses.forEach(minus => minus.addEventListener('click', function(){
        $('.loader-wrapper').css('display', 'flex');
        let id = minus.getAttribute("data-id");
        let quanNumber = Number(minus.parentElement.parentElement.children[1].value);
        let productPriceHTML = document.querySelector(`.ProductPrice[name='pr${id}']`).innerHTML;
        let productPrice = productPriceHTML.slice(1);
        if(quanNumber > 1){
            setTimeout(function(){
            minus.parentElement.parentElement.children[1].value = quanNumber - 1;
            let totalProductPrice = document.querySelector(`.totalProductPrice[name='pr${id}']`);
            totalProductPrice.innerHTML = `$${productTotalPrice(quanNumber - 1, productPrice)}`;
            updateCart(id, quanNumber - 1);
            totalPrice();
            $('.loader-wrapper').css('display', 'none');
            }, 1000);
        }
        else{
            $('.loader-wrapper').css('display', 'none');
        }
    }));
}
function totalPrice(){
    let total = 0;
    $('.totalProductPrice').each(function(){
        total += Number($(this).html().slice(1));
    });
    $('#cartTotalPrice').html(`$${total}`);
    $('#cartSubTotalPrice').html(`$${total}`);
}
function updateCart(id,quantity){
    $.ajax({
        url: "models/updateCart.php",
        method: "POST",
        data: {
            id: id,
            quantity: quantity,
            btnUpdate: true
        },
        success: function(response, status, xhr) {
            if(status === "success"){
              return true;
            }
        },
        error: function(xhr, status, error){
            if(status === "error")
            {
               alert(xhr.responseText);
            }
        }
    });
}
function addToCart(id, quantity){
    let cartNum = parseInt($('.numberProductsCart').html());

    $.ajax({
        url: "models/addToCart.php",
        method: "POST",
        data: {
            id: id,
            quantity: quantity,
            btnCart: true
        },
        success: function (response) {
            $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
            $('.modal-body').html(response);
            $('#exampleModal').modal('show');
            $('.numberProductsCart').html(cartNum + 1);
            setTimeout(function () {
                $('.modal-body').html("");
                $('#exampleModal').modal('hide');
            }, 2500);
        },
        error: function (xhr, status, error) {
            $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
            $('.modal-body').html(xhr.responseText);
            $('#exampleModal').modal('show');
            setTimeout(function () {
                $('.modal-body').html("");
                $('#exampleModal').modal('hide');
            }, 2500);
        }
    });
    // $('.Popup').css({'right': '0'});
    // setTimeout(function(){
    //     $('.Popup').css({'right': '-200%'});
    // }, 1800);
}
function adminDelete(button,table){
    let id = button.data('id');
    $.ajax({
        url: `models/adminDelete.php`,
        method: "POST",
        data: {
            table: table,
            id: id,
            btnDelete: true
        },
        success: function (response) {
                button.parent().parent().remove();
                $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
                $('.modal-body').html(response);
                $('#exampleModal').modal('show');
                setTimeout(function () {
                    $('.modal-body').html("");
                    $('#exampleModal').modal('hide');
                }, 2000);
        },
        error: function (xhr, status, error) {
            $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
            $('.modal-body').html(xhr.responseText);
            $('#exampleModal').modal('show');
            setTimeout(function () {
                $('.modal-body').html("");
                $('#exampleModal').modal('hide');
            }, 2000);
        }
    })
}
function adminEdit(button,table){
    let id = button.data("id");
    $("#exampleModal").css("display", "block");
    $.ajax({
        url: "models/adminEdit.php",
        type: "POST",
        data: {
            id: id,
            table: table,
            updateBtn: true
        },
        success: function (response) {
            makeModal(JSON.parse(response),"update");

        }
    });
}
var columnsWithDates = ["created_at","updated_at","date_of_sending","date_from","date_to"];
var columnsWithImages = ["image_src","avatar"];
function makeModal(object, operation){
    let modal = document.querySelector(".modal-body");
    $('.modal-body').css({'color':'black','font-size':'16px','text-align':'left'});
    modal.innerHTML = "";
    console.log(object);
    if(operation == "insert"){
        for(let i of object){
            console.log(i);
            let split = i.split("_");
            if(split[1] == "id" || split[2] == "id"){
                $.ajax({
                    url: "models/getDataForID.php",
                    type: "GET",
                    data: {
                        table: split[1] == "id" ? split[0] : split[1],
                        getData: true
                    },
                    success: function (response) {
                        console.log(response);
                        let html = "";
                        html += `
                        <div class="form-group mb-4">
                        ${split[1] == "id" ? split[0] : split[0] + " " +  split[1]}: <select name="${i}" class="form-control inputForInsert">`;
                        let data = JSON.parse(response); 
                        let objectKeys = Object.keys(data[0]);
                        let valueToDisplay = [objectKeys[1]];
                        let idColumn = [objectKeys[0]];
                        if(split[0] == "discount"){
                            html +=`<option value="">Choose...</option>`;
                        }
                        for(let j = 0;j<data.length;j++){
                           
                                html += `<option value="${data[j][idColumn]}">${data[j][valueToDisplay]}</option>`;                          
                        }
                        html += `</select></div>`;
                        modal.innerHTML += html;
                    }
                })
            }
            else {
                if(columnsWithImages.includes(i)){
                    modal.innerHTML += `
                    <div class="form-group mb-4">
                    ${i}:<input type="file" name="${i}" class="form-control uploadImg  inputForInsert" value="">
                    </div>
                    `;
                }
                else if(columnsWithDates.includes(i)){
                    modal.innerHTML += `
                    <div class="form-group mb-4">
                    ${i}:<input type="date" name="${i}" class="form-control  inputForInsert" value="">
                    </div>
                    `;
                }
                else{
                    modal.innerHTML += `
                    <div class="form-group mb-4">
                    ${i}:<input type="text" name="${i}" class="form-control  inputForInsert" value="">
                    </div>`;
                }
               
            }
        }
    }
    else{
        let count = 0;
        for(let i in object[0]){
            let split = i.split("_");
            if((split[1] == "id" || split[2] == "id") && count > 0){
                    $.ajax({
                        url: "models/getDataForID.php",
                        type: "GET",
                        data: {
                            table: split[1] == "id" ? split[0] : split[1],
                            getData: true
                        },
                        success: function (response) {
                            let html = "";
                                html += `
                                <div class="form-group mb-4">
                                ${split[1] == "id" ? split[0] : split[0] + " " +  split[1]}: <select name="${i}" class="form-control inputForUpdate">`;
                                let data = JSON.parse(response); 
                                console.log(data);
                                let objectKeys = Object.keys(data[0]);
                                let valueToDisplay = [objectKeys[1]];
                                let idColumn = [objectKeys[0]];
                                if(split[0] == "discount"){
                                    html +=`<option value="">Choose...</option>`;
                                }
                                for(let j = 0;j<data.length;j++){
                                    console.log(object[0][idColumn])
                                    html += `<option ${data[j][idColumn] == object[0][idColumn] ? "selected" : ""} value="${data[j][idColumn]}">${data[j][valueToDisplay]}</option>`;
                                }
                                html += `</select></div>`;
                                modal.innerHTML += html;
                            }
                    })
            }
            else if((split[1] == "id" || split[2] == "id") && count == 0){
                $.ajax({
                    url: "models/getDataForID.php",
                    type: "GET",
                    data: {
                        table: split[1] == "id" ? split[0] : split[1],
                        getData: true
                    },
                    success: function (response) {
                        let html = "";
                            html += `
                                <input type="hidden" name="${i}" class="inputForUpdate" value="${object[0][i]}">
                          `;
                            modal.innerHTML += html;
                        }
                })
        }
            else {
                    if(columnsWithImages.includes(i)){
                        modal.innerHTML += `
                        <div class="form-group mb-4">
                        ${i}:<input type="file" name="${i}" class="form-control uploadImg inputForUpdate" value="">
                        <img src="assets/${object[0][i]}${i == "image_src" ? "_small.png" : ""}" alt="${i}" class="img-fluid mt-2">
                        </div>
                        `;
                    }
                    else if(i == "description"){
                        modal.innerHTML += `
                        <div class="form-group mb-4">
                        ${i}:<textarea name="${i}" class="form-control inputForUpdate" rows="7" cols="10" >${object[0][i] != null ? object[0][i] : ""}</textarea>
                        </div>`;
                    }
                    else if(columnsWithDates.includes(i)){
                        if(object[0][i] != null){
                            let date = new Date(object[0][i]);
                            date = date.toISOString().split("T")[0];
                            console.log(date);
                            modal.innerHTML += `
                            <div class="form-group mb-4">
                            ${i}:<input type="date" name="${i}" class="form-control inputForUpdate" value="${date}">
                            </div>
                            `;
                        }
                        else{
                            modal.innerHTML += `
                            <div class="form-group mb-4">
                            ${i}:<input type="date" name="${i}" class="form-control inputForUpdate" value="">
                            </div>
                            `;
                        }
                       
                    }
                    else{
                        modal.innerHTML += `
                        <div class="form-group mb-4">
                        ${i}:<input type="text" name="${i}" class="form-control inputForUpdate" value="${object[0][i] != null ? object[0][i] : ""}">
                        </div>`;
                    }
            }
            count++;
        }
    }
    var url = new URL(window.location.href);
    var table = url.searchParams.get('table');
    setTimeout(function() {
        modal.innerHTML +=`<input type="submit" value="${operation == "insert" ? "Insert" : "Update"}" name="${operation == "insert" ? "btnInsert" : "btnUpdate"}" data-table='${table}' class='${operation == "insert" ? "btnInsert" : "btnUpdate"} btn btn-primary mt-3'>
        <input type="submit" value="Cancel" name="Cancel" class='btnCancel btn btn-danger mt-3'>
        `;
        if(operation == "update"){
            $(".btnUpdate").click(function(){
                let table = $(this).data("table");
                var data = new FormData();
                if(table == "users" || table == "products"){
                    jQuery.each(jQuery('.uploadImg')[0].files, function(i, file) {
                        data.append(`${table == "users" ? "avatar" : "image_src"}`, file);
                    });
                }
                jQuery.each(jQuery('.inputForUpdate'), function(i, file) {
                    if(file.type != "file"){
                        data.append(file.name, file.value);
                    }
                });
                data.append("table", table);
                // data = JSON.stringify(data);
                $.ajax({
                    url: "models/adminUpdate.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (response) {
                        $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
                        $('.modal-body').html(response);
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html(xhr.responseText);
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                        }, 2000);
                    }
                });
            });
        }
        else{
            $(".btnInsert").click(function(){
                let table = $(this).data("table");
                let inputs = $(".inputForInsert");
                var data = new FormData();
                if(table == "users" || table == "products"){
                    jQuery.each(jQuery('.uploadImg')[0].files, function(i, file) {
                        data.append(`${table == "users" ? "avatar" : "image_src"}`, file);
                    });
                }
                jQuery.each(jQuery('.inputForInsert'), function(i, file) {
                    if(file.type != "file"){
                        data.append(file.name, file.value);
                    }
                });
                data.append("table", table);
                $.ajax({
                    url: "models/adminInsert.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (response) {
                        $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
                        $('.modal-body').html(response);
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                            location.reload();
                        }, 2000);
                       
                    },
                    error: function (xhr, status, error) {
                        $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html(xhr.responseText);
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                            $('.modal-body').css({'color':'black','font-size':'16px','text-align':'left'});
                        }, 2000);
                    }
                });
            });
        }
        $(".btnCancel").click(function(){
            $(".modal-body").html("");
            $('#exampleModal').modal('hide');
            $('#exampleModal').css("display","none");
        });
    },500);
}
function adminCreate(button,table){
    $("#exampleModal").css("display","block");
    $.ajax({
        url: "models/getColumnsForInsert.php",
        type: "GET",
        data: {
            table: table,
            insertBtn: true
        },
        success: function (response) {
            console.log(response);
            makeModal(JSON.parse(response),"insert");
        }
    });
}
function getProductDiscount(product){
    return product.discount_id == null ? "" : "<p class='discount-perc'>-" + product.discount_percent + "%</p>";
}
function productInStock(product){
    return product.in_stock == 1 ? `<a class="btn btn-outline-dark btn-squar addToCart" data-id="${product.product_id}"><i class='fa fa-shopping-cart'></i></a>` : "";
}
function getProductDiscountedPrice(product){
    let discount = product.discount_percent;
    let price = product.price;
    return price - (price * discount / 100);
}
function getProductActivePrice(product){
    let price =  product.discount_id == null ? product.price : getProductDiscountedPrice(product);
    return `<h5 class="ml-2">${price}&euro;</h5>`;
}
function getProductOldPrice(product){
    let price =  product.discount_id == null ? "" : `${product.price}&euro;`;
    return `<h6 class="text-muted ml-2 oldPrice"><del>${price}</del></h6>`;
}
function getProductRating(rating){
    let html = "";
    for ($i = 1; $i <= rating; $i++) {
        html += "<i class='fas fa-star text-primary'></i>";
    }
    for ($i = 1; $i <= 5 - rating; $i++) {
        html += "<i class='far fa-star text-primary'></i>";
    }
    return html;
}
function validateInput(input,regex,type){
    if(input.value == ''){
            $(input).next().html('This field is required and can not be empty.');
            return false;
    }
    else if(!regex.test(input.value)){
        switch(type){
            case 'firstname':
                $(input).next().html('Incorrect first name format. First letter must be capital and name must contain only letters. Example: John');
                return false;
            case 'lastname':
                $(input).next().html('Incorrect last name format. First letter must be capital and name must contain only letters. Example: Doe');
                return false;
            case 'email':
                $(input).next().html('Incorrect email format. Example: jhondoe@gmail.com');
                return false;
            case 'phone':
                $(input).next().html('Incorrect phone format. Example: +381601234567 or 0601234567');
                return false;
            case 'address':
                $(input).next().html('Incorrect address format. Example: Street 1');
                return false;
            case 'country':
                $(input).next().html('Incorrect country format. First letter must be capital and name must contain only letters. Example: Serbia');
                return false;
            case 'city':
                $(input).next().html('Incorrect city format. First letter must be capital and name must contain only letters. Example: Belgrade');
                return false;
            case 'zip':
                $(input).next().html('Incorrect zip format. Example: 11000');
                return false;
        }
    }
    else{
        $(input).next().html('');
        return true;
    }
}
function validation(){
    const firstName = document.querySelector('#firstname');
    const lastName = document.querySelector('#lastname');
    const email = document.querySelector('#email');
    const phone = document.querySelector('#phone');
    const address = document.querySelector('#address');
    const city = document.querySelector('#city');
    const country = document.querySelector('#country');
    const zip = document.querySelector('#zipcode');
    const nameRegex = /^[A-ZČĆĐŽŠ][a-zčćđžš]{2,}(\s[A-ZČĆĐŽŠ][a-zčćđžš]{2,})*$/;
    const emailRegex = /^[a-z0-9]+[\._]?[a-z0-9]+[@]\w+[.]\w{2,3}$/;
    const phoneRegex = /^(\+381|0)[6-9][0-9]{7,8}$/;
    const addressRegex = /[A-Z0-9][a-zA-Z0-9\s]{5,}?/;
    const countryRegex = /^[A-Z][a-z]{2,}(\s[A-Za-z]{2,})*$/;
    const cityRegex = /^[A-ZČĆĐŽŠ][a-zčćđžš]{2,}(\s[A-ZČĆĐŽŠ][a-zčćđžš]{2,})*$/;
    const zipRegex = /^[0-9]{5}$/;
    firstName.addEventListener('blur',function(){
        validateInput(this,nameRegex,'firstname');
    });
    lastName.addEventListener('blur',function(){
        validateInput(this,nameRegex,'lastname');
    });
    email.addEventListener('blur',function(){
        validateInput(this,emailRegex,'email');
    });
    phone.addEventListener('blur',function(){
        validateInput(this,phoneRegex,'phone');
    });
    address.addEventListener('blur',function(){
        validateInput(this,addressRegex,'address');
    });
    country.addEventListener('blur',function(){
        validateInput(this,countryRegex,'country');
    });
    city.addEventListener('blur',function(){
        validateInput(this,cityRegex,'city');
    });
    zip.addEventListener('blur',function(){
        validateInput(this,zipRegex,'zip');
    });
    country.addEventListener('change',function(){
        validateInput(this,'','country');
    });
    document.querySelector('#placeOrder').addEventListener('click',function(e){
        e.preventDefault();
        let totalPrice = document.querySelector('#cartTotalPrice').innerHTML;
        let price = Number(totalPrice.slice(1));
        let valid = true;

        valid = validateInput(phone,phoneRegex,'phone');
        valid = validateInput(firstName,nameRegex,'firstname');
        valid = validateInput(lastName,nameRegex,'lastname');
        valid = validateInput(email,emailRegex,'email');
        valid = validateInput(address,addressRegex,'address');
        valid = validateInput(country,countryRegex,'country');
        valid = validateInput(city,cityRegex,'city');
        valid = validateInput(zip,zipRegex,'zip');
        if($('.custom-radio input:checked').length == 0){
            this.previousElementSibling.innerHTML = 'You must choose payment method.';
            valid = false;
        }
        else{
            this.previousElementSibling.innerHTML = '';
            valid = true;
        }
        if(valid){
            $.ajax({
                url: "models/insertOrder.php",
                method: "POST",
                data: {
                    totalPrice: price,
                    paymentID: $('.custom-radio input:checked').val(),
                    btnOrder: true,
                },
                success: function(response, status, xhr) {
                    if(status === "success"){
                        console.log("Order inserted")
                        let checkProducts = document.querySelectorAll('.prName');
                        console.log(checkProducts)
                        checkProducts.forEach(function (product) {
                            console.log(document.querySelector(`.prQuantity[data-id="${product.dataset.id}"]`))
                            let quantity = document.querySelector(`.prQuantity[data-id="${product.dataset.id}"]`).innerHTML;
                            if(product === checkProducts[checkProducts.length - 1]){
                                $.ajax({
                                    url: "models/insertOrderDetails.php",
                                    method: "POST",
                                    data: {
                                        orderID: response,
                                        productID: product.dataset.id,
                                        quantity: quantity,
                                        lastProduct: true,
                                    },
                                    success: function (response, status, xhr) {
                                            if(status === 'success'){
                                                $('.checkoutRow').addClass('text-center');
                                                $('.checkoutRow').html('<h3 class="text-center text-success">Thank you for your order! You can check it at Your account page!</h3>');
                                                $('.numberProductsCart').html(0);

                                            }
                                    },
                                    error: function (xhr, status, error) {
                                        alert('Error: ' + error);
                                    }
                                })
                            }
                            else{
                                $.ajax({
                                    url: "models/insertOrderDetails.php",
                                    method: "POST",
                                    data: {
                                        orderID: response,
                                        productID: product.dataset.id,
                                        quantity: quantity,
                                    },
                                    success: function (response, status, xhr) {
                                        return true;
                                    },
                                    error: function (xhr, status, error) {
                                        alert('Error: ' + error);
                                    }
                                })
                            }
                        });
                    }
                }
            })
        }
    });
}
const urlParams = new URLSearchParams(window.location.search);
const myParam = urlParams.get('page');
if(myParam == null || myParam == 'shop'){
    function compareProducts(product){
        console.log(product);
        $("#compareWrapper").show();    
        let compareDivProduct = document.querySelectorAll('#compareWrapper .compareProduct');
        let divCounter = 0;
        for(let i=0;i<compareDivProduct.length;i++){
            let img = compareDivProduct[i].querySelector('img');
            if(img == null){
                divCounter++;
                compareDivProduct[i].innerHTML = `
                <img src="assets/${product.image_src}_small.png" alt="${product.name}" class="img-fluid">
                <p class="compProdName">${product.name}</p>
                <i class="fa fa-times closeCompareSingle" data-id="${i}" data-prid="${product.product_id}"></i>
                `;
                compareDivProduct[i].style.border = 'none';
                $(document).on('click','.closeCompareSingle',function(){
                        let id = $(this).data('id');
                        let compareDivProduct = document.querySelectorAll('#compareWrapper .compareProduct');
                        compareDivProduct[id].innerHTML = '';
                        compareDivProduct[id].style.border = '1px dashed #f2be00';
                        let productID = $(this).data('prid');
                        let compare = JSON.parse(localStorage.getItem("compare"));
                        compare = compare.filter(function (el) {
                            return el.id != productID;
                        });
                        if(compare.length == 0){
                            localStorage.removeItem("compare");
                            $("#compareWrapper").hide();                     
                        }
                        if(compare.length == 1){
                            document.querySelector('#btnCompare').remove();
                        }
                        localStorage.setItem("compare", JSON.stringify(compare));
                });
                break;
            }  
        }
        let html = document.querySelector('#compareWrapper').innerHTML;
        if(document.querySelector('#btnCompare') == null && JSON.parse(localStorage.getItem("compare")).length > 1){
         
           html += `<a href="index.php?page=compare" id="btnCompare" class="btn btn-primary">Compare</a>`;
        }
        document.querySelector('#compareWrapper').innerHTML = html;
    }
    function makeCompare(){
        if(localStorage.getItem("compare")){
            let compare = JSON.parse(localStorage.getItem("compare"));
            for(let i of compare){
                $.ajax({
                    url: "models/getProductForCompare.php",
                    method: "GET",
                    data: {
                        id: i.id,
                        compare: true
                    },
                    success: function(response, status, xhr) {
                        compareProducts(JSON.parse(response));
                    },
                })
            }
        }
    }
    $(".addToCompare").on('click',function(e){
        let id = $(this).data('id');
        if(localStorage.getItem('compare') == null){
            let obj = [];
            obj[0] = {
                id: id
            }
            localStorage.setItem('compare',JSON.stringify(obj));
            $.ajax({
                url: "models/getProductForCompare.php",
                method: "GET",
                data: {
                    id: id,
                    compare: true
                },
                success: function(response, status, xhr) {
                    compareProducts(JSON.parse(response));
                },
            })
        }
        else{
            let compare = JSON.parse(localStorage.getItem('compare'));
            let hasProduct = false;
            if(compare.length == 3){
                $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html("You can compare maximum 3 products!");
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                        }, 2000);
                hasProduct = true;
            }
            else{
                for(let i in compare){
                    if(compare[i].id == id){
                         $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html("This product is already in compare list!");
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                        }, 2000);
                        hasProduct = true;
                    }
                }
                if(!hasProduct){
                    compare.push({
                        id: id
                    });
                    localStorage.setItem('compare',JSON.stringify(compare));
                    $.ajax({
                        url: "models/getProductForCompare.php",
                        method: "GET",
                        data: {
                            id: id,
                            compare: true
                        },
                        success: function(response, status, xhr) {
                            compareProducts(JSON.parse(response));
                        },
                    })
                }
            }
        }
    });
    let compare = JSON.parse(localStorage.getItem("compare"));
    if(compare == null || compare.length == 0){
        localStorage.removeItem("compare");
        $("#compareWrapper").hide();                     
    }
    else{
        $("#compareWrapper").show(); 
        makeCompare();
    }
}
if(myParam == "compare"){
     function printComparedProducts(products){
        let html = '';
        products.forEach(product => {
        html += `
        <div class="singlePrForCompare w-25" data-id="${product.product_id}">
        <img src="assets/${product.image_src}_large.png" class="img-fluid w-75" alt="${product.image_alt}">
        <div class="mt-3 w-100">
                <div class="bg-light p-30">
                    <a class="linkCompairedPr" href="index.php?page=product-detail&id=${product.product_id}">${product.name}</a>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                        ${getProductRating(Math.round(product.Rating))}
                        </div>
                        <small class="pt-1">
                        ${product.NumberOfReviews} review                        
                        </small>
                    </div>
                    <h6 class="productInStock mb-3">
                        Availability: 
                    <span class="${product.in_stock == 1 ? "inStock" : "OutOfStock"}">${product.in_stock == 1 ? "In stock" : "Out of stock"}</span>                    
                    </h6>
                    <h5 class="font-weight-semi-bold mb-4">
                        Category: ${product.category_name}                   </h5>
                    <div class="product-price">
                       ${product.discount_id == null ? `<h3 class="font-weight-semi-bold mb-4 activePrice">${product.price}&euro;</h3>` : `<h3 class="font-weight-semi-bold mb-4 activePrice">${product.price - (product.price * product.discount_percent / 100)}&euro;</h3>`}
                       ${product.discount_id == null ? "" : `<h3 class="text-muted ml-2 oldPrice"><del>${product.price}&euro;</del></h3>`}
                    </div>
                    ${product.discount_id == null ? "" : "<p class='discount-perc-single'>-" + product.discount_percent + "%</p>"}                                     
                                        </div>
        </div>
    </div>`;
        $.ajax({
            url: "models/getProductForCompare.php",
            method: "GET",
            data: {
                id: product.product_id,
                category_name: product.category_name,
                getCharacteristics: true
            },
            success: function(response, status, xhr) {
                let div = document.querySelector(".singlePrForCompare[data-id='" + product.product_id + "']");
                div.innerHTML += response;
            }
        });
       });
       $("main").html(html);
       localStorage.removeItem("compare");
    };
    let compare = JSON.parse(localStorage.getItem("compare"));
    let ids = [];
    if(compare.length == 0){
        location.href = "index.php?page=shop";
    }
    else{
        for(let i of compare){
          ids.push(i.id);
        }
        $.ajax({
            url: "models/getComparedProducts.php",
            method: "GET",
            data: {
                ids: ids
            },
            success: function(response, status, xhr) {
                printComparedProducts(JSON.parse(response));
            }
        });
    }
}
if(myParam != null && myParam != 'shop'){
$("#compareWrapper").remove();
}
if(myParam == null){
    markActivePage('Home');
    removeEmptyPrice();
    removeEmptyDiscount();
}
if(myParam == 'shop'){
    markActivePage('Shop');
    removeEmptyPrice();
    removeEmptyDiscount();
    $(document).ready(function(){
        let inputs = document.querySelectorAll('#filterForm input');
        inputs.forEach(function(input){
            input.checked = false;
        });
    });
    function getFilteredProducts(){
        let activePage = $('.pagination .active').text();
            $.ajax({
                url: "models/filterProducts.php",
                method: "GET",
                data: {
                    minPrice: $('.range-min').val(),
                    maxPrice: $('.range-max').val(),
                    category: getClickedFilters('category-filter'),
                    brand: getClickedFilters('brand-filter'),
                    sort: $('#product-sort').val(),
                    viewpage: $('.pagination .active').text()
                },
                success: function(response){
                    printProducts(JSON.parse(response));
                    let products = JSON.parse(response);
                    let numOfProducts = products[products.length-1].numOfProducts;
                    makePagination(numOfProducts,activePage);
                    removeEmptyDiscount();
                    removeEmptyPrice();
                    sendPage();
                    window.scrollTo({top: 0, behavior: 'smooth'});
                },
                error: function(xhr, status, error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
            setTimeout(function(){
            addToWishlist();
            $('.addToCart').on('click', function(){
                let id = $(this).data('id');
                addToCart(id, 1);
            });
            }, 2000);
    }
    function printProducts(products){
        let html = "";
        if(products.length == 0){
            html = `<h3 class="px-3 py-3">There are no products for this filters!<h3>`;
        }
        else{
            for(let i = 0;i<products.length-1;i++){
                html += `
                    <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                        <div class="product-item bg-light mb-4">
                            ${getProductDiscount(products[i])}
                            <div class="product-img position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="assets/${products[i].image_src}_large.png" alt="${products[i].image_alt}">
                                <div class="product-action">
                                    ${productInStock(products[i])}
                                    <a class="btn btn-outline-dark btn-square addToWish" data-id="${products[i].product_id}"><i class="far fa-heart"></i></a>
                                    <a class="btn btn-outline-dark btn-square addToCompare" data-id="${products[i].product_id}"><i class="fa fa-sync-alt"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <a class="h5 text-decoration-none text-truncate" href="index.php?page=product-detail&id=${products[i].product_id}">${products[i].name}</a>
                                <div class="d-flex align-items-center justify-content-center mt-2">          
                                   ${getProductActivePrice(products[i])}
                                   ${getProductOldPrice(products[i])}
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-1">
                                    ${getProductRating(Math.round(products[i].Rating))}
                                    <small class="text-muted ml-2"> (${products[i].NumberOfReviews})</small>
                                </div>
                             </div>
                        </div>
                    </div>
                    `;
            }

        }
        $('.pr-wrapper').html(html);
        $(document).on("click",".addToCompare",function(){
                 let id = $(this).data('id');
        if(localStorage.getItem('compare') == null){
            let obj = [];
            obj[0] = {
                id: id
            }
            localStorage.setItem('compare',JSON.stringify(obj));
            $.ajax({
                url: "models/getProductForCompare.php",
                method: "GET",
                data: {
                    id: id,
                    compare: true
                },
                success: function(response, status, xhr) {
                    compareProducts(JSON.parse(response));
                },
            })
        }
        else{
            let compare = JSON.parse(localStorage.getItem('compare'));
            let hasProduct = false;
            if(compare.length == 3){
                 $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html("You can compare maximum 3 products!");
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                        }, 2000);
                hasProduct = true;
            }
            else{
                for(let i in compare){
                    if(compare[i].id == id){
                        $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                        $('.modal-body').html("This product is already in compare list!");
                        $('#exampleModal').modal('show');
                        setTimeout(function () {
                            $('.modal-body').html("");
                            $('#exampleModal').modal('hide');
                        }, 2000);
                        hasProduct = true;
                    }
                }
                if(!hasProduct){
                    compare.push({
                        id: id
                    });
                    localStorage.setItem('compare',JSON.stringify(compare));
                    $.ajax({
                        url: "models/getProductForCompare.php",
                        method: "GET",
                        data: {
                            id: id,
                            compare: true
                        },
                        success: function(response, status, xhr) {
                            compareProducts(JSON.parse(response));
                        },
                    })
                }

            }
            
        }   
        })
    }
    function makePagination(length,activePage){
        let html = "";
        console.log(activePage);
        let productsHtml = document.querySelector('.pr-wrapper').innerHTML;
        let numOfPages = Math.ceil(length / 9);
        html = `<div class="col-12"><nav><ul class="pagination justify-content-center">`;
        for (let i = 1; i <= 1; i++){
            if(activePage == 1){
                html += `<li class="page-item active pageLink"><a class="page-link" data-page="${i}">${i}</a></li>`;
                continue;
            }
            else{
                html +=  `<li class="page-item pageLink"><a class="page-link" data-page="${i}">${i}</a></li>`;
            }
        }
        for (let i = 2; i <= numOfPages; i++)
        {
           if(i == activePage){
                html += `<li class="page-item active pageLink"><a class="page-link" data-page="${i}">${i}</a></li>`;
                continue;
            }
            html +=  `<li class="page-item pageLink"><a class="page-link" data-page="${i}">${i}</a></li>`;
        }
        html += "</ul></nav></div>";
        productsHtml += html;
        console.log(html);
        $('.pr-wrapper').html(productsHtml);

    }
    const rangeInput = document.querySelectorAll(".range-input input"),
        priceInput = document.querySelectorAll(".price-input input"),
        range = document.querySelector(".slider .progress");
    let priceGap = 200;
    priceInput.forEach(input =>{
        input.addEventListener("input", e =>{
            let minPrice = parseInt(priceInput[0].value),
                maxPrice = parseInt(priceInput[1].value);

            if((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max){
                if(e.target.className === "input-min"){
                    rangeInput[0].value = minPrice;
                    range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                }else{
                    rangeInput[1].value = maxPrice;
                    range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                }
            }
        });
    });
    rangeInput.forEach(input =>{
        input.addEventListener("input", e =>{
            let minVal = parseInt(rangeInput[0].value),
                maxVal = parseInt(rangeInput[1].value);
            if((maxVal - minVal) < priceGap){
                if(e.target.className === "range-min"){
                    rangeInput[0].value = maxVal - priceGap
                }else{
                    rangeInput[1].value = minVal + priceGap;
                }
            }else{
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
            }
        });
    });
    $('.range-min').on('mouseup', function(){
        getFilteredProducts();
    });
    $('.range-max').on('mouseup', function(){
        getFilteredProducts();
    });
    $('.category-filter').on('change', function(){
           $('.pagination li').each(function(){
                $(this).removeClass('active');
           });
           $('.pagination li:first-child').addClass('active');
        getFilteredProducts();
    });
    $('.brand-filter').on('change', function(){
        $('.pagination li').each(function(){
             $(this).removeClass('active');
        });
        $('.pagination li:first-child').addClass('active');
     getFilteredProducts();
 });
    $('#product-sort').on('change', function(){
        $('.pagination li').each(function(){
            $(this).removeClass('active');
        });
        $('.pagination li:first-child').addClass('active');
        getFilteredProducts();
    });
    sendPage();
}
if(myParam == 'contact'){
    markActivePage('Contact');
    function validateInputMessage(input,regex,type){
        if(input.value == ''){
                $(input).next().html(`${type} is required and can not be empty.`);
                return false;
        }
        else if(!regex.test(input.value)){
            switch(type){
                case 'Full name':
                    $(input).next().html('Incorrect full name format. Example: John Doe');
                    return false;
                case 'Email':
                    $(input).next().html('Incorrect email format. Example: jhondoe@gmail.com');
                    return false;
                case 'Subject':
                    $(input).next().html('Incorrect subject format. First letter must be capital and subject must contain only letters. Example: Message');
                    return false;
            }
        }
        else{
            $(input).next().html('');
            return true;
        }
    }
    function validationMessage(){
        const fullName = document.querySelector('input[name="fullName"]');
        const email = document.querySelector('input[name="email"]');
        const subject = document.querySelector('input[name="subject"]');
        const message = document.querySelector('textarea[name="message"]');
        let mailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        let fullNameRegex = /^[A-Z][a-z]{2,11}(\s[A-Z][a-z]{2,11})+$/;
        let subjectRegex = /^[A-Z][a-z]{2,}$/;
        fullName.addEventListener('blur',function(){
            validateInputMessage(this,fullNameRegex,'Full name');
        });
        email.addEventListener('blur',function(){
            validateInputMessage(this,mailRegex,'Email');
        });
        subject.addEventListener('blur',function(){
            validateInputMessage(this,subjectRegex,'Subject');
        });
        message.addEventListener('blur',function(){
            if (message.value.length > 10) {
                $('#message').next().html("");
            } else {
                $('#message').next().html("Message must contain at least 10 characters!");
            }
        });
        document.querySelector('#sendMessageButton').addEventListener('click',function(e){
            e.preventDefault();
            let valid = true;
            valid = validateInputMessage(fullName,fullNameRegex,'Full name');
            valid = validateInputMessage(email,mailRegex,'Email');
            valid = validateInputMessage(subject,subjectRegex,'Subject');
            if (message.value.length > 10) {
                $('#message').next().html("");
                valid = true;
            } else {
                $('#message').next().html("Message must contain at least 10 characters!");
                valid = false;
            }
            if(valid){
                    let fullName = $('#fullName').val();
                    let email = $('#email').val();
                    let subject = $('#subject').val();
                    let message = $('#message').val();
                    $.ajax({
                        url: "models/insertMessage.php",
                        method: "POST",
                        data: {
                            fullName: fullName,
                            email: email,
                            subject: subject,
                            message: message,
                            btnContact: true
                        },
                        success: function (response) {
                            $('#success').html(response);
                            setTimeout(function () {
                                $('#success').html('');
                            }, 2000);
                            $('#fullName').val('');
                            $('#email').val('');
                            $('#subject').val('');
                            $('#message').val('');
                        }
                    });
                }
        });
    }
    validationMessage();
}
if(myParam == null || myParam == 'shop' || myParam == 'product-detail'){
    addToWishlist();
    $('.addToCart').on('click', function(){
        let id = $(this).data('id');
        addToCart(id, 1);
    });
}
if(myParam == 'cart'){
    changeProductQuantity();
    totalPrice();
    $('.deleteProductCart').on('click', function(){
        $('.loader-wrapper').css('display', 'flex');
        let id = $(this).data('id');
        let parent = this.parentElement.parentElement;
        let cartNum = parseInt($('.numberProductsCart').html());
        $.ajax({
            url: "models/deleteProductCart.php",
            method: "POST",
            data: {
                id: id,
                btnDelete: true
            },
            success: function(response, status, xhr) {
                if(status === "success"){
                    setTimeout(function() {
                        parent.remove();
                        $('.numberProductsCart').html(cartNum - 1);
                        if (cartNum - 1 == 0) {
                            $('.cartWrapper').html('<h3 class=\'mx-auto\'>You cart is empty.</h3>');
                        }
                        totalPrice();
                        $('.loader-wrapper').css('display', 'none');
                        $('.Popup').css({'background-color': '#05e626', 'color': 'white'});
                        $('.Popup p').html(response);
                    }, 1000);
                }
                else{
                    $('.loader-wrapper').css('display', 'none');
                }
            },
            error: function(xhr, status, error){
                if(status === "error")
                {
                    $('.Popup').css({'background-color': 'red', 'color': 'white'});
                    $('.Popup p').html(xhr.responseText);
                }
            }
        });
        setTimeout(function() {
            $('.Popup').css({'right': '0'});
        }, 1200);
        setTimeout(function(){
            $('.Popup').css({'right': '-200%'});
        }, 3000);
    });
}
if(myParam == 'product-detail'){
   $('.single-pr-rating i').on('click',function(){
       let starNum = Number($(this).data('rate'));
       let clickedStars = document.querySelectorAll(`.single-pr-rating .fas`);
       if(starNum < clickedStars.length){
              for(let i = starNum; i < clickedStars.length; i++){
                clickedStars[i].className = 'far fa-star';
              }
       }
       let stars = document.querySelectorAll('.single-pr-rating i');
       for(let i = 1; i <= starNum; i++){
           stars[i-1].className = 'fas fa-star';
       }
   });
    addToWishlist();
    let plus = document.querySelector('.btn-plus');
    let minus = document.querySelector('.btn-minus');
    if(plus){
        plus.addEventListener('click', function(){
            let quanNumber = Number(plus.parentElement.parentElement.children[1].value);
            plus.parentElement.parentElement.children[1].value = quanNumber + 1;
        })
    }
    if(minus){
        minus.addEventListener('click', function(){
            let quanNumber = Number(minus.parentElement.parentElement.children[1].value);
            if(quanNumber > 1){
                minus.parentElement.parentElement.children[1].value = quanNumber - 1;
            }
        })
    }
    $('.addToCartSingleProduct').on('click', function(){
        let id = $(this).data('id');
        let quantity = $('#prQuantity').val();
        addToCart(id, quantity);
        $('#prQuantity').val('1');
    });
   function validateReview(input){
       let fullNameRegex = /^[A-ZČĆŽŠĐ][a-zčćžšđ]{2,11}(\s[A-ZČĆŽŠĐ][a-zčćžšđ]{2,11})+$/;
       let mailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
         if(input == "message"){
              if($('#message').val() == ""){
                $('#message').next().html("Message cannot be empty field!");
                return false;
              }
              else{
                $('#message').next().html("");
                  return true;
              }
         }
            else if(input == "name"){
                if($('#name').val() == ""){
                    $('#name').next().html("Name cannot be empty field!");
                    return false;
                }
                else if(!fullNameRegex.test($('#name').val())){
                    $('#name').next().html("Incorrect full name format. Example: John Doe");
                    return false;
                }
                else{
                    $('#name').next().html("");
                    return true;
                }
            }
            else if(input == "email"){
                if($('#email').val() == ""){
                    $('#email').next().html("Email cannot be empty field!");
                    return false;
                }
                else if(!mailRegex.test($('#email').val())) {
                    $('#email').next().html("Incorrect email format. Example: jhondoe@gmail.com");
                    return false;
                }
                else{
                    $('#email').next().html("");
                    return true;
                }
         }


   }
   $('#message').on('blur',function(){
       validateReview("message");
   });
   $('#name').on('blur',function(){
       validateReview("name");
   });
   $('#email').on('blur',function(){
       validateReview("email");
   });
   $('#postReview').on('click',function(e){
         e.preventDefault();
         let name = $('#name').val();
         let email = $('#email').val();
         let message = $('#message').val();
         let stars = document.querySelectorAll('.single-pr-rating i');
         let starNum = 0;
         let validReview = true;
         for(let i = 0; i < stars.length; i++){
              if(stars[i].className == 'fas fa-star'){
                starNum++;
              }
         }
         if(starNum == 0){
           $('.rate-error').html('You must rate product!');
         }
         validReview = validateReview("name");
         validReview = validateReview("email");
         validReview = validateReview("message");
       console.log(validReview)
         if(validReview == true && starNum != 0){
              $.ajax({
                url: "models/insertReview.php",
                method: "POST",
                data: {
                     message: message,
                     starNum: starNum,
                     productID: $('#postReview').data('prid'),
                     btnReview: true
                },
                success: function(response){
                     $('#review-msg').html(response);
                     if(response == "Review added successfully!"){
                            $('#review-msg').css('color','green');
                            let todaysDate = new Date();
                            let day = todaysDate.getDate();
                            let month = todaysDate.toLocaleString('default', { month: 'long' });
                            let year = todaysDate.getFullYear();
                            let revWrapper = document.querySelector('.reviews-wrapper');
                            let wrapperHtml = revWrapper.innerHTML;
                            let newReview = `<div class="media mb-4">
                                        <div class="media-body">
                                            <h6>${$('#name').val()}<small> - <i>${day} ${month} ${year}</i></small></h6>
                                            <div class="text-primary mb-2 user-rate">
                                            ${calculateStars(starNum)}
                                            </div>
                                            <p>${$('#message').val()}</p>
                                        </div>
                                    </div>`;
                            revWrapper.innerHTML = wrapperHtml + newReview ;
                     }
                     else{
                            $('#review-msg').css('color','red');
                     }
                     setTimeout(function(){
                          $('#review-msg').html('');
                     },3000);
                     $('#message').val('');
                     $('.rate-error').html('');
                     $('.single-pr-rating i').each(function(){
                          $(this).removeClass('fas');
                          $(this).addClass('far');
                     });
                },
                error: function(xhr, status, error){
                     console.log(xhr);
                     console.log(status);
                     console.log(error);
                }
              });
         }
   });
}
if(url.includes("register")){
    function validateRegisterInput(value,inputname){
        console.log(value);
        let inputError = document.querySelector(`input[name="${inputname}"]`).parentElement.nextElementSibling;
        console.log(inputError);
        if(value == ""){
            inputError.innerHTML = `${inputname} cannot be empty field!`;
        }
        else{
            switch (inputname) {
                case "First name":
                    let firstNameRegex = /^[A-ZČĆŽĐŠ][a-zčćžšđ]{2,11}$/;
                    if(!firstNameRegex.test(value)){
                        inputError.innerHTML = `${inputname} is not valid! Example: John`;
                        return false;
                    }
                    else{
                        inputError.innerHTML = "";
                        return true;
                    }
                    break;
                case "Last name":
                    let lastNameRegex = /^[A-ZČĆŽĐŠ][a-zčćžšđ]{2,11}$/;
                    if(!lastNameRegex.test(value)){
                        inputError.innerHTML = `${inputname} is not valid! Example: Smith`;
                        return false;
                    }
                    else{
                        inputError.innerHTML = "";
                        return true;
                    }
                    break;
                case "Email":
                    let mailRegex = /^[\w]+[\.\_\-\w\d]*\@[\w]+([\.][\w]+)+$/;
                    if(!mailRegex.test(value)){
                        inputError.innerHTML = `${inputname} is not valid! Example: jhondoe@gmail.com`;
                    }
                    else{
                        inputError.innerHTML = "";
                        return true;
                    }
                    break;
                case "Password":
                    let passwordRegex = /^(?=.*[A-ZČĆŽĐŠa-zčćžšđ])(?=.*\d)[A-Za-z\d]{8,}$/;
                    if(!passwordRegex.test(value)){
                        inputError.innerHTML = `${inputname} must be at least 8 characters long and must contain at least one letter and one number! Example: John1234`;
                        return false;
                    }
                    else{
                        inputError.innerHTML = "";
                        return true;
                    }
                    break;
                    case "Retype password":
                        let password = document.querySelector('input[name="Password"]').value;
                        if(value != password){
                            inputError.innerHTML = `Passwords do not match!`;
                            return false;
                        }
                        else{
                            inputError.innerHTML = "";
                            return true;
                        }
                        break;
                case "Username":
                    let usernameRegex = /^[A-Za-z0-9]{3,}$/;
                    if(!usernameRegex.test(value)){
                        inputError.innerHTML = `${inputname} must be at least 3 characters long and can contain only letters and numbers! Example: John123`;
                        return false;
                    }
                    else{
                        inputError.innerHTML = "";
                        return true;
                    }
                    break;
                default:
                    break;
            }
        }
    }

   $('.validate-input input').each(function(){
       $(this).on("blur",function(){
           console.log($(this).attr('name'));
           validateRegisterInput($(this).val(), $(this).attr('name'));
       });
   });
    $('.submitBtn').on('click',function(e){
        e.preventDefault();
        let valid = true;
        $('.validate-input input').each(function(){
            if(!validateRegisterInput($(this).val(), $(this).attr('name'))){
                valid = false;
            }
        });
        if(valid){
            $.ajax({
                url: "../models/registerUser.php",
                method: "POST",
                data: {
                    firstName: $('input[name="First name"]').val(),
                    lastName: $('input[name="Last name"]').val(),
                    email: $('input[name="Email"]').val(),
                    password: $('input[name="Password"]').val(),
                    retypedPassword: $('input[name="Retype password"]').val(),
                    username: $('input[name="Username"]').val(),
                    submit: true
                },
                success: function (data) {
                    if (data == "success") {
                        $('.validate-input input').each(function(){
                            $(this).val("");
                        });
                        $('.input100').each(function(){
                            $(this).removeClass('has-val');
                        })
                        $('#responseMsg').css({'color':'green','font-size':'20px'});
                        $('#responseMsg').html('You have successfully registered!');
                        setTimeout(function () {
                            window.location.href= "../index.php";
                        }, 3000);
                    }
                    else {
                        $('#responseMsg').html(data);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    });
}
if(url.includes("login")){
    $('.submitBtn').on('click',function(e){
        e.preventDefault();
        $.ajax({
            url: "loginUser.php",
            method: "POST",
            data: {
                username: $('input[name="Username"]').val(),
                password: $('input[name="Password"]').val(),
                submit: true
            },
            success: function (response,status) {
               location.href = "../index.php";
            },
            error: function (xhr, status, error) {
                $('#responseMsg').html(xhr.responseText);
            }
        });
    });
}
if(myParam == 'wishlist'){
        $('.deleteProductWish').on('click',function(e){
        e.preventDefault();
        parent = this.parentElement.parentElement.parentElement.parentElement;
        let id = $(this).data('id');
        let wishNum = parseInt($('.numberProductsWish').html());
        $.ajax({
            url: "models/deleteProductFromWishlist.php",
            method: "POST",
            data: {
                id: id,
                btnDelete: true
            },
            success: function (response,status) {
                if (status === "success") {
                    parent.remove();
                    $('.Popup').css({'background-color': '#05e626', 'color': 'white'});
                    $('.Popup p').html(response);
                    $('.numberProductsWish').html(wishNum-1);
                    if(wishNum-1 == 0){
                        $('.wish-wrapper').html('<h3 class=\'mx-auto\'>You don\'t have products in your wishlist.</h3>');
                    }
                }
                else {
                    $('.Popup p').html(response);
                }
            },
            error: function (xhr, status, error) {
                $('.Popup').css({'background-color': 'red', 'color': 'white'});
                $('.Popup p').html(error);
            }
        });
        $('.Popup').css({'right': '0'});
        setTimeout(function(){
            $('.Popup').css({'right': '-200%'});
        }, 3000);
    });
}
if(myParam == 'checkout'){
    totalPrice();
    validation();
}
if(myParam == 'account'){
    document.querySelector('#updateInfos').addEventListener('click',function(e){
        e.preventDefault();
        const phone = document.querySelector('#phone');
        const address = document.querySelector('#address');
        const city = document.querySelector('#city');
        const country = document.querySelector('#country');
        const zip = document.querySelector('#zip');
        const phoneRegex = /^(\+381|0)[6-9][0-9]{7,8}$/;
        const addressRegex = /[A-Z0-9][a-zA-Z0-9\s]{5,}?/;
        const countryRegex = /^[A-Z][a-z]{2,}(\s[A-Za-z]{2,})*$/;
        const cityRegex = /^[A-ZČĆĐŽŠ][a-zčćđžš]{2,}(\s[A-ZČĆĐŽŠ][a-zčćđžš]{2,})*$/;
        const zipRegex = /^[0-9]{5}$/;
        let valid = true;
        valid = validateInput(phone,phoneRegex,'phone');
        valid = validateInput(address,addressRegex,'address');
        valid = validateInput(country,countryRegex,'country');
        valid = validateInput(city,cityRegex,'city');
        valid = validateInput(zip,zipRegex,'zip');
        if(valid){
            $.ajax({
                url: "models/updateUserInfos.php",
                method: "POST",
                data: {
                    phone: phone.value,
                    address: address.value,
                    city: city.value,
                    country: country.value,
                    zip: zip.value,
                    update: true
                },
                success: function (response,status) {
                    if (status === "success") {
                        phone.disabled = true;
                        address.disabled = true;
                        city.disabled = true;
                        country.disabled = true;
                        zip.disabled = true;
                        $('#updateInfos').css('display','none');
                        $('#editInfos').css('display','inline-block');
                        $('.Popup').css({'background-color': '#05e626', 'color': 'white'});
                        $('.Popup p').html("You have successfully updated your informations.");
                    }
                    else {
                        $('.Popup p').html(response);
                    }
                },
                error: function (xhr, status, error) {
                    $('.Popup').css({'background-color': 'red', 'color': 'white'});
                    $('.Popup p').html(error);
                }
            })
            $('.Popup').css({'right': '0'});
            setTimeout(function(){
                $('.Popup').css({'right': '-200%'});
            }, 2000);
        }
    });
    if($('#editInfos').is(":visible")){
        $('#updateInfos').css('display','none');
        $('#editInfos').on('click',function(e){
            e.preventDefault();
            $(this).css('display','none');
            $('#updateInfos').css('display','inline-block');
            $('#cancelInfos').css('display','inline-block');
            $('#phone').prop('disabled',false);
            $('#address').prop('disabled',false);
            $('#city').prop('disabled',false);
            $('#country').prop('disabled',false);
            $('#zip').prop('disabled',false);
        });
    }
    $('.moreDetails').on('click',function(e){
        e.preventDefault();
        $(this).parent().parent().next().slideToggle();
    });
    $('#editProfile').on('click',function(e){
        e.preventDefault();
        $('#userInfo').css('display','block');
        $('#orders').css('display','none');
    });
    $('#orderHistory').on('click',function(e){
        e.preventDefault();
        $('#userInfo').css('display','none');
        $('#orders').css('display','block');
    });
    $('input[type="file"]').on('change', function() {
        var data = new FormData();
        jQuery.each(jQuery('#file')[0].files, function(i, file) {
            data.append('avatar', file);
        });
        $.ajax({
            url: 'models/addUserAvatar.php',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response,status) {
                if(status === "success"){
                    $('#photoMsg').css('color','green');
                    $('#photoMsg').html("You changed your profile photo.");
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                $('#photoMsg').css('color','red');
                $('#photoMsg').html(xhr.responseText);
            }
        });
    });
}
function ajaxCallback(url, method, data, withFile, result){
    data = data == 0 ? "" : data;

    var ajaxObj = {
        url: "models/" + url,
        method: method,
        data: data,
        dataType: "json",
        success: result,
        error: function(xhr) {
          console.log(xhr);
          if (xhr.status == 422) {
            let errors = xhr.responseJSON.poruka;
            for (let error of errors) {
              console.log(error);
            }
          }
          if (xhr.status == 500) {
            console.log(xhr.responseJSON.poruka);
          }
          if (xhr.status == 404) {
            console.log("Nije dozvoljen pristup.");
          }
        }
      };
      if (withFile != "0") {
        ajaxObj.contentType = false;
        ajaxObj.processData = false;
      }
      $.ajax(ajaxObj);
   
}
if(myParam == 'admin'){
    $('.btnDelete').on('click',function(e) {
        e.preventDefault();
        let table = $(this).data('table');
        let confirm = window.confirm(`Are you sure you want to delete this row from "${table.toUpperCase()}" table?`);
        if(confirm){
            adminDelete($(this),$(this).data('table'));
        }
    });
    $('.btnCreate').on('click',function(e) {
        e.preventDefault();
        adminCreate($(this),$(this).data('table'));

    });
    $('.btnEdit').on('click',function(e) {
        e.preventDefault();
        adminEdit($(this),$(this).data('table'));
    });
    $(".btnUnban").on("click", function(e) {
        e.preventDefault();
        let id = $(this).data("id");
        let username = $(this).data("name");
        let confirm = window.confirm(`Are you sure you want to unban ${username}?`);
        if (confirm) {
            $.ajax({
                url: "models/unbanUser.php",
                type: "POST",
                data: {
                    id: id
                },
                success: function (response) {
                    $('.modal-body').css({'color':'green','font-size':'20px','text-align':'center'});
                    $('.modal-body').html(response);
                    $('#exampleModal').modal('show');
                    setTimeout(function () {
                        $('.modal-body').html("");
                        $('#exampleModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function (xhr, status, error) {
                    $('.modal-body').css({'color':'red','font-size':'20px','text-align':'center'});
                    $('.modal-body').html(xhr.responseText);
                    $('#exampleModal').modal('show');
                    setTimeout(function () {
                        $('.modal-body').html("");
                        $('#exampleModal').modal('hide');
                    }, 2000);
                }
            });
        }
      });
}