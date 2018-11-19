<?php
namespace Datalist\Test\TestCase\View\Widget;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\StringTemplate;
use Datalist\View\Widget\DatalistJsWidget;

/**
 * App\Model\Behavior\DatalistBehavior Test Case
 */
class DatalistJsWidgetTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = Configure::read('datalistJs');
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
        $templates['datalistJs'] = $config;
        $templates['option'] = '<option value="{{value}}"{{attrs}}>{{text}}</option>';
        $this->templates = new StringTemplate($templates);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Datalist);
        parent::tearDown();
    }

    public function testRenderNoOptions()
    {
        $datalist = new DatalistJsWidget($this->templates);
        $data = [
            'id' => 'day-id',
            'name' => 'day_id',
            'options' => []
        ];
        $result = $datalist->render($data, $this->context);

        $this->assertContains(
            '<input type="text" id="__day-id" name="__day_id" list="datalist-day-id" autocomplete="off">',
            $result
        );
        $this->assertContains(
            '<datalist id="datalist-day-id" id="day-id"></datalist>',
            $result
        );
    }

    public function testRenderWithOptions()
    {
        $datalist = new DatalistJsWidget($this->templates);
        $data = [
            'id' => 'day-id',
            'name' => 'day_id',
            'templateVars' => [],
            'options' => ['m' => 'Monday', 't' => 'Tuesday']
        ];
        $result = $datalist->render($data, $this->context);
        $this->assertContains(
            '<datalist id="datalist-day-id" id="day-id">'
                . '<option data-value="m">Monday</option><option data-value="t">Tuesday</option>'
                . '</datalist>',
            $result
        );

        $this->assertContains(
            '<script>
                if (CakePHP_datalist === undefined) {
                    var CakePHP_datalist = {};
                }
                
                CakePHP_datalist["day-id"] = {};
                [].forEach.call(
                    document.querySelectorAll("#datalist-day-id option"), 
                    function(element){
                        CakePHP_datalist["day-id"][element.value] = element.getAttribute("data-value");
                    });
                
                document.getElementById("__day-id")
                    .addEventListener("blur", 
                        function (e) {
                            document.getElementById("day-id").value = CakePHP_datalist["day-id"][e.target.value] 
                                ? CakePHP_datalist["day-id"][e.target.value] 
                                : document.getElementById("__day-id").value;
                        });
            </script>',
            $result
        );

    }
}
