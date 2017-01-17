<h1>Store API In PHP</h1>
<h2>Dev Notes</h2>
<b>
<ol>
    <li>Please import SQL file into mysql database before using this demo.</li>
    <li>Please Update values in Config.php file accordingly.</li>
    <li>This Demo Uses Singleton Pattern for Database Operations.</li>
    <li>Cookie File is created , to keep session alive between curl requests,also helps if browser restricts creating cookie.</li>
</ol>
 </b>
<h3> This API Package Deals with CRUD Operations on Product,Category And Cart Entities, Also Stores Cart Data (Sessions) into Table.
</h3>
<br>
<h4> Package Consist of following Classes and Methods</h4>
<p><b>Products</b></p>
<ul>
    <li>getProducts</li>
        <p>Returns List of Added Products</p>
        <p>Syntax:</p>
            <p>$strMethod = 'products/getProducts';
            <br>
            <p>postDataToApi($strMethod);</p>
            </p>
            <p>
                Return: 
                <b>
                                {
                  "status": true,
                  "Products": [
                    {
                      "id": "18",
                      "name": "United Colors of Benetton Men's Polo",
                      "description": "Made in India",
                      "price": "640.54",
                      "discount": "2",
                      "category": "Women's Clothing",
                      "added_on": "2017-01-18 02:39:45"
                    },
                    {
                      "id": "19",
                      "name": "Solimo Spectra Stripe",
                      "description": "Made in India",
                      "price": "856",
                      "discount": "15",
                      "category": "Memory Cards",
                      "added_on": "2017-01-18 02:39:45"
                    },
                    {
                      "id": "20",
                      "name": "Essence Mens Party Wear Shoes",
                      "description": "Made in India",
                      "price": "856",
                      "discount": "15",
                      "category": "Softwares",
                      "added_on": "2017-01-18 02:39:45"
                    }
                  ]
                }
                </b>
            </p>
            
            
    <li>addProduct</li>
    <p>Adds Product </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
            array(
                'name'=>"United Colors of Benetton Men's Polo",
                'description' =>"Made in India",
                'price'=>640.54,
                'discount'=>2,
                'category_id'=>3,

            ),
            array(
                'name'=>"Solimo Spectra Stripe",
                'description' =>"Made in India",
                'price'=>856,
                'discount'=>15,
                'category_id'=>4,

            ),
            array(
                'name'=>"Essence Mens Party Wear Shoes",
                'description' =>"Made in India",
                'price'=>856,
                'discount'=>15,
                'category_id'=>5,

            )
        );
    ';
    <p>$strMethod = 'products/addProduct';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {
            "status": true,
            "msg": "3 : Products Added Successfully",
            "invalid_data": []
            }
        </b>
    </p>
    
    
    
    <li>updateProduct</li>
    <p>Updates Product </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
        'id'=>1,
        'data' =>array(
        'name' =>'New Product name',
        'description' =>'Product update Api',
        'price' =>800,
        )
        );
    ';
    <p>$strMethod = 'products/updateProduct';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {
            "status": true,
            "msg": "Products Updated Successfully",
            "invalid_data": []
            }
        </b>
    </p>
    
    
    
    <li>deleteProduct</li>
    <p>Deletes Product </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
        'id'=>1,
        );
    <p>$strMethod = 'products/deleteProduct';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {
            "status": true,
            "msg": "Product with Id : 1 deleted Successfully",
            "invalid_data": []
            }
        </b>
    </p>
    
</ul>

<br>
<br>

<p><b>Category</b></p>
<ul>
    <li>getCategories</li>
        <p>Returns List of Categories</p>
        <p>Syntax:</p>
            <p>$strMethod = 'category/getCategories';
            <br>
            <p>postDataToApi($strMethod);</p>
            </p>
            <p>
                Return: 
                <b>
                    {
                    "status": true,
                    "Categories": [
                    {
                    "id": "3",
                    "name": "Women's Clothing",
                    "description": "Women's Clothing",
                    "tax": "3%",
                    "added_on": "0000-00-00 00:00:00"
                    },
                    {
                    "id": "4",
                    "name": "Memory Cards",
                    "description": "Made in India",
                    "tax": "3%",
                    "added_on": "2017-01-17 18:24:26"
                    },
                    {
                    "id": "5",
                    "name": "Softwares",
                    "description": "Made in India",
                    "tax": "3%",
                    "added_on": "2017-01-17 18:24:26"
                    }
                    ]
                    }
                </b>
            </p>
            
            
    <li>addCategory</li>
    <p>Adds New category </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
        array(
        'name'=>"Smart phones",
        'description' =>"Made in India",
        'tax'=>3,
        ),
        array(
        'name' => "Bags",
        'description' => "Made in India",
        'tax' => 2,
        ),
        array(
        'name' => "Shoes",
        'description' => "Made in India",
        'tax' => 5,
        )
        );
    <p>$strMethod = 'category/addCategory';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {"status":true,"msg":"3 : Categories Added Successfully","invalid_data":[]}
        </b>
    </p>
    
    
    
    <li>updateCategory</li>
    <p>Updates Category </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
            'id'=>9,
            'data'=>array(
                'name' =>'Water Purifier'
            )
        );
    <p>$strMethod = 'category/updateCategory';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {"status":true,"msg":"Products Updated Successfully","invalid_data":[]}
            </b>
    </p>
    
    
    
    <li>deleteCategory</li>
    <p>Deletes Category And All Products Associated to that category </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
            'id'=>9
    );
    <p>$strMethod = 'category/deleteCategory';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
           {"status":true,"msg":"Category with Id : 9 deleted Successfully","invalid_data":[]}


        </b>
    </p>
    
</ul>



<p><b>Cart</b></p>
<ul>
    <li>getCart</li>
        <p>Returns Details of Products Added to Cart</p>
        <p>Syntax:</p>
            <p>$strMethod = 'cart/getCart';;
            <br>
            <p>postDataToApi($strMethod);</p>
            </p>
            <p>
                Return: 
                <b>
                    {
                    "status": true,
                    "products": {
                    "0": {
                    "product_id": "2",
                    "product_name": "Solimo Spectra Stripe",
                    "product_description": "Made in India",
                    "product_price": "856",
                    "product_qty": 3,
                    "product_total_price": 2568,
                    "product_discount": "15%",
                    "product_total_discounted_price": 2183,
                    "product_category": "Memory Cards",
                    "product_category_tax": "3%",
                    "product_total_tax_include": 2248
                    },
                    "1": {
                    "product_id": "4",
                    "product_name": "Kinley",
                    "product_description": "Water Bottle",
                    "product_price": "22",
                    "product_qty": 1,
                    "product_total_price": 22,
                    "product_discount": "0%",
                    "product_total_discounted_price": 22,
                    "product_category": "Softwares",
                    "product_category_tax": "3%",
                    "product_total_tax_include": 23
                    },
                    "grand_total": 2271
                    },
                    "msg": "Cart Data Retrieved successfully."
                    }
                </b>
            </p>
      
            
    <li>getCartTotal</li>
        <p>Returns grand total</p>
        <p>Syntax:</p>
            <p>$strMethod = 'cart/getCartTotal';
            <br>
            <p>postDataToApi($strMethod);</p>
            </p>
            <p>
                Return: 
                <b>
                        {"status":true,"grand_total":7517,"msg":"Cart Total Retrieved successfully."}


                </b>
            </p>
      
            <li>getCartTotalDiscount</li>
            <p>Returns total discount applied to cart</p>
            <p>Syntax:</p>
            <p>$strMethod = 'cart/getCartTotalDiscount';
                <br>
            <p>postDataToApi($strMethod);</p>
</p>
<p>
    Return: 
    <b>
        {"status":true,"total_discount":"15%","msg":"Cart Total Disount  Retrieved successfully."}
    </b>
</p>


 <li>getCartTotalTax</li>
            <p>Returns total category tax applied to cart</p>
            <p>Syntax:</p>
            <p>$strMethod = 'cart/getCartTotalTax';
                <br>
            <p>postDataToApi($strMethod);</p>
</p>
<p>
    Return: 
    <b>
        {"status":true,"total_tax":"6%","msg":"Cart Total Tax  Retrieved successfully."}

    </b>
</p>
            
    <li>addToCart</li>
    <p>Adds Products To Cart</p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
            array(
                'id'=>2,
                'qty'=>1
            ),
            array(
                'id'=>4,
                'qty'=>1
            ),
            array(
        'id' => 2,
        'qty' => 1
    ),
    );
    <p>$strMethod = 'cart/addToCart';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {
            "status": true,
            "msg": "Products Added To Cart Successfully",
            "invalid_data": []
            }
        </b>
    </p>
    
    
    
    <li>updateCart</li>
    <p>Updates Product Quantity for specified product Id </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
        array(
        'id'=>2,
        'qty'=>7
        )

        );
    <p>$strMethod = 'cart/updateCart';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
            {"status":true,"msg":"Cart Updated...!"}
            </b>
    </p>
    
    
    
    <li>removeFromCart</li>
    <p>Remove Specified Product from cart </p>
    <p>Syntax:</p>
    <p>$arrPostData = array(
            'id'=>2
    );
    <p>$strMethod = 'category/removeFromCart';
    <br>
    postDataToApi($strMethod,$arrPostData);</p>
    </p>
    <p>
        Return: 
        <b>
          {"status":true,"msg":"Products Removed From Cart Successfully"}
        </b>
    </p>
    <li>clearCart</li>
    <p>Remove all items from cart </p>
    <p>Syntax:</p>
    <p>$strMethod = 'category/clearCart';
    <br>
    postDataToApi($strMethod);</p>
    </p>
    <p>
        Return: 
        <b>
          {"status":true,"msg":"Cart Cleared Successfully."}

        </b>
    </p>
</ul>