<?php
return [
    'datalistJs' => '<input type="text" id="__{{id}}" name="__{{name}}" list="datalist-{{id}}" autocomplete="off"{{inputAttrs}}>'
        . '<datalist id="datalist-{{id}}"{{datalistAttrs}}>{{content}}</datalist>'
        . '<input type="hidden" name="{{name}}" id="{{id}}">'
        . '<script>
                if (CakePHP_datalist === undefined) {
                    var CakePHP_datalist = {};
                }
                
                CakePHP_datalist["{{id}}"] = {};
                [].forEach.call(
                    document.querySelectorAll("#datalist-{{id}} option"), 
                    function(element){
                        CakePHP_datalist["{{id}}"][element.value] = element.getAttribute("data-value");
                    });
                
                document.getElementById("__{{id}}")
                    .addEventListener("blur", 
                        function (e) {
                            document.getElementById("{{id}}").value = CakePHP_datalist["{{id}}"][e.target.value] 
                                ? CakePHP_datalist["{{id}}"][e.target.value] 
                                : document.getElementById("__{{id}}").value;
                        });
            </script>'
];
