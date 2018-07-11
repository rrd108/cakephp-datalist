<?php
namespace Datalist\Test\TestCase\View\Widget;

use Cake\TestSuite\TestCase;
use Cake\View\StringTemplate;
use Cake\View\View;
use Datalist\View\Widget\DatalistWidget;

/**
 * App\Model\Behavior\DatalistBehavior Test Case
 */
class DatalistWidgetTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        //read template into $config variable
        require './vendor/rrd108/cakephp-datalist/config/form-templates.php';
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
        $templates = $config + ['option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>'];
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
        $datalist = new DatalistWidget($this->templates);
        $data = [
            'id' => 'day-id',
            'name' => 'day_id',
            'options' => []
        ];
        $result = $datalist->render($data, $this->context);

        $this->assertContains(
            '<input type="text" id="__day-id" name="__day_id" list="_day-id" autocomplete="off">',
            $result
        );
        $this->assertContains(
            '<datalist id="_day-id" id="day-id"></datalist>',
            $result
        );
        $this->assertContains(
            '<input type="hidden" name="day_id" id="day-id">',
            $result
        );
        $this->assertContains(
            'CakePHP_datalist["day-id"][element.value] = element.getAttribute("data-value");',
            $result
        );
        $this->assertContains(
            'document.getElementById("day-id").value = CakePHP_datalist["day-id"][e.target.value]',
            $result
        );
    }

    public function testRenderWithOptions()
    {
        $datalist = new DatalistWidget($this->templates);
        $data = [
            'id' => 'day-id',
            'name' => 'day_id',
            'templateVars' => [],
            'options' => ['m' => 'Monday', 't' => 'Tuesday']
        ];
        $result = $datalist->render($data, $this->context);
        $this->assertContains(
            '<datalist id="_day-id" id="day-id">'
                . '<option data-value="m">Monday</option><option data-value="t">Tuesday</option>'
                . '</datalist>',
            $result
        );
    }
}
