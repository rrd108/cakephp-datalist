<?php
$config = [
    'datalist' => '<input type="text" id="__{{id}}" name="__{{name}}" list="_{{id}}" autocomplete="off">'
        . '<datalist id="_{{id}}"{{attrs}}>{{content}}</datalist>'
        . '<input type="hidden" name="{{name}}" id="{{id}}">'
        . '<script>
                if (CakePHP_datalist === undefined) {
                    var CakePHP_datalist = {};
                }
                
                CakePHP_datalist["{{id}}"] = {};
                [].forEach.call(
                    document.querySelectorAll("#_{{id}} option"), 
                    function(element){
                        CakePHP_datalist["{{id}}"][element.value] = element.getAttribute("data-value");
                    });
                
                document.getElementById("__{{id}}")
                    .addEventListener("blur", 
                        function (e) {
                            document.getElementById("{{id}}").value = CakePHP_datalist["{{id}}"][e.target.value] 
                                ? CakePHP_datalist["{{id}}"][e.target.value] 
                                : document.getElementById("__{{id}}").value;
                            console.log(document.getElementById("{{id}}").value);
                        });
            </script>'
];
