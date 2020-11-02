<!DOCTYPE html>
<html lang="en">
<head>
    <?php session_start();?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bacsilog</title>
    <link rel="icon" href="./img/logo.png">

    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/vue.js"></script>
    <script src="./js/axios.min.js"></script>

</head>
<body>
    <div id="globalApp">
        <div class="container-top" >
            
            <img src="./img/logo.png" alt="Bacsilog Logo">
            <div class="text-container">
                <h1>Bacsilog</h1>
                <h2>Point of Sale</h2>
            </div>
            <!-- VUE date today -->
            <div class="left">
                <h3>{{ date }}</h3> 
                <button class="btn btn-danger btn-block" @click="deleteAll()">Clear</button>
            </div>
        </div>
        <div class="holder" id="h" v-on:click="showMenu()">
            <div class="menuIcon">
            <div class="rectangle"></div>
            <div class="rectangle"></div>
            <div class="rectangle"></div>
            </div>
        </div>
        <div class="menu-container" id="mc">
            <div class="closeIcon">
                <p v-on:click="hideMenu()">x</p>
            </div>
            <div class="title">
               <h1>Menu Codes</h1>
            </div>
            <div class="menu-container">
                <?php
                    include 'php/conn.php';
                    $rs = mysqli_query($conn,"SELECT * FROM product");
                    while ($row = mysqli_fetch_assoc($rs)){
                ?>
                <div class="amenus">
                    <div class="amenu">
                        <div class="menu-name">
                            <p><?php echo $row["name"];?></p>
                        </div>
                        <div class="menu-code">
                            <p><?php echo $row["code_name"];?></p>
                        </div>
                        <div class="menu-price">
                            <p><?php echo $row["price"];?>.00</p>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="container-body">
        <div class="container-fluid">
            <div class="row">
                <div id="app" class="col-md-6">
                    <div class="show-detail">
                        <div class="qty">
                            <!-- VUE total quantity -->
                            <h2 id="qty">{{ totalQty }}</h2>
                            <h3>qty</h3>
                        </div>
                        <div class="tp">
                            <!-- VUE total price  -->
                            <h2 id="tp">{{ totalPrice }}</h2>
                            <h3>total</h3>
                        </div>
                        <!-- <button class="btn btn-warning" @click="saveOrder()">Done</button> -->
                        <div class="button-done" v-on:click="saveOrder()">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="svg-inline--fa fa-check fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#c8030a" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                        </div>
                    </div> 
                   <div class="user-input">
                        <input class="form-control" type="text" placeholder="Code" v-model="int_code" style="text-transform:uppercase" @input="int_code = $event.target.value.toUpperCase()"/>
                        <input class="form-control" type="number" placeholder="Quantity" v-model="int_qty" min="1"/>
                        <button class="btn"  v-on:click="add_currentOrder()">Enter</button>
                    </div> 
                   <div class="order-detail">
                       <div class="order-number">
                           <h2>
                                order # 
                                <!-- PHP current order number -->
                                <span id="current_order_number">
                                <?php
                                    $rs = mysqli_query($conn,"SELECT max(order_number)+1 FROM `orders` ");
                                    while ($row = mysqli_fetch_assoc($rs)){
                                        echo $row["max(order_number)+1"];
                                    }
                                ?>
                                </span>
                            </h2>
                       </div>
                       <table class="cOrder-table">
                           <tr v-for="(item, index) in order">
                                <td><p :key="item.qty">{{item.qty}}</p></td>
                                <td><p :key="item.price">{{item.price}}.00</p></td>
                                <td><p :key="item.name">{{item.name}}</p>
                                <td><button class="btn" @click="delete_currentOrder(index)">x</button></td>
                            </tr>   
                        </table>
                   </div>
                </div>
                <div class="col-md-6">
                    <div class="order-log">
                        <div class="order-title">
                            <h2>orders</h2>
                        </div>
                        <?php
                        $rs = mysqli_query($conn,"SELECT * FROM orders");
                        while ($row = mysqli_fetch_assoc($rs)){
                        ?>
                        <div class="order-log-detail">
                            <table class="order-table">
                                <tr>
                                    <td><p>#<?php echo $row["order_number"];?></p></td> 
                                    <td><p><?php echo $row["total_qty"];?> <span>QTY</span></p></td>
                                    <td><p><?php echo $row["total_price"];?></p></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="order-bottom">
                            <h2>total</h2>
                            <!-- grand total order -->
                            <div class="totalDetail-holder">
                                <p>
                                    <?php
                                        $rs = mysqli_query($conn,"SELECT count(*) FROM `orders` ");
                                        while ($row = mysqli_fetch_assoc($rs)){
                                            echo $row["count(*)"];
                                        }
                                    ?>
                                </p>
                                <!-- grand total -->
                                <p>
                                    <?php
                                        $rs = mysqli_query($conn,"SELECT sum(total_price) FROM `orders` ");
                                        while ($row = mysqli_fetch_assoc($rs)){
                                            echo $row["sum(total_price)"];
                                        }
                                    ?>
                                    .00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
    </div>
    <div class="container-footer">
        <p>BacsilogPandi 2020 Copyright © </p>
    </div>
    <script>
        new Vue({
            el: '#globalApp',
            data: { 
                date: ""
            },
            methods:{
                date_function: function () {
                    var currentDate = new Date();
                    console.log(currentDate);
                    var formatted_date = new Date().toJSON().slice(0,10).replace(/-/g,'/');
                    this.date = formatted_date;
                },
                deleteAll: function(){
                axios.post('php/ajax.php', {
                        request: "deleteAll"
                    })
                    .then(function (response) {
                        alert(response.data);
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                showMenu: function(){
                    document.getElementById("h").style.transform = "translate(-2000px,0)";
                    document.getElementById("mc").style.transform = "translate(0,0)";
    
                },
                hideMenu: function(){
                    document.getElementById("h").style.transform = "translate(0,0)";
                    document.getElementById("mc").style.transform = "translate(100%,0)";
                }
            
            },
            
            mounted () {
            this.date_function()
            }
        });

        new Vue({
            el: '#app',
            data: { 
                totalQty: 0,
                totalPrice: 0,
                orderNumber: 0,

                int_qty: 1,
                int_code: "",
                

                order: [
                ],

                purchase: {
                    qty: 1,
                    price: null,
                    name: null
                }
            },
            created: function () {
                this.orderNumber  = parseInt(document.getElementById("current_order_number").innerHTML);
                
                if(this.orderNumber){
                }else{
                    this.orderNumber = 1;
                    document.getElementById("current_order_number").innerHTML = 1;
                }
                
                document.getElementById("h").style.transform = "translate(0,0)";
                document.getElementById("mc").style.transform = "translate(100%,0)";

            },
            methods:{
                add_currentOrder: function (){
                    if(this.int_qty > 0){
                        
                        if(
                        this.int_code == "moja lang malakas" 
                        
                            <?php 
                            $rs = mysqli_query($conn,"SELECT * FROM product");
                            while ($row = mysqli_fetch_assoc($rs)){
                            ?>

                            || this.int_code == "<?php echo $row["code_name"];?>"
                            
                            <?php
                            }
                           
                            ?>
                        ){          
                            <?php 
                            $rs = mysqli_query($conn,"SELECT * FROM product");
                            while ($row = mysqli_fetch_assoc($rs)){?>
                            if(this.int_code == "<?php echo $row["code_name"];?>"){
                                this.purchase.qty = parseInt(this.int_qty);
                                this.purchase.price = parseInt(<?php echo $row["price"];?>);
                                this.purchase.name = "<?php echo $row["name"];?>";

                                this.order.push({...this.purchase});
                            }
                            <?php }?>
                            
                        }else{
                            alert("Invalid Product Code!");
                        }
                        

                        
                    }else{
                        alert("Invalid Quantity!");
                    }
                    

                    this.update();

                    
                    
                },

                delete_currentOrder: function(index){
                    this.order.splice(index, 1);
                    this.update();
                },
                saveOrder: function(){
                    if(this.totalQty != '' && this.totalPrice != '' && this.orderNumber != ''){
                        axios.post('php/ajax.php', {
                            request: "saveOrder",
                            orderNumber: this.orderNumber,
                            totalQty: this.totalQty,
                            totalPrice: this.totalPrice
                        })
                        .then(function (response) {

                       
                            alert(response.data);
                            location.reload();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }else{
                        alert('Fill all fields.');
                    }
                },
                
                update(){
                    this.totalQty = 0;
                    this.totalPrice = 0;
                    for (var i=0;i<this.order.length;i++){
                        this.sumPrice = this.order[i].price * this.order[i].qty;
                        this.totalQty += this.order[i].qty;
                        this.totalPrice += this.sumPrice;
                    }
                }
                
                
            }
        });


    </script>

</body>
</html>