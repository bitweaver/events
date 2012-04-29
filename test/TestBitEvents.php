<?php
require_once('../../kernel/setup_inc.php');
require_once(EVENTS_PKG_PATH.'BitEvents.php');

class TestBitEvents extends Test {

    var $test;
    var $id;
    var $count;

    function TestBitEvents()
    {
        global $gBitSystem;
        Assert::equalsTrue($gBitSystem->isPackageActive( 'events' ), 'Package not active');
    }

    function testCreateItem()
    {
        $this->test = new BitEvents();
        Assert::equalsTrue($this->test != NULL, 'Error during initialisation');
    }

    function testGetItems()
    {
	$filter = array();
        $list = $this->test->getList($filter);
        $this->count = count($list);
        Assert::equalsTrue(is_array($list));
    }

    function testStoreItem()
    {
	$newItemHash = array(
		"title" => "Test Title",
		"description" => "Test Description",
		"data" => "Test Text"
	);
        Assert::equalsTrue($this->test->store($newItemHash));
    }

    function testIsValidItem()
    {
        Assert::equalsTrue($this->test->isValid());
    }

    function testNullItem()
    {
	$this->id = $this->test->mEventsId;
        $this->test = NULL;
        Assert::equals($this->test, NULL);
    }

    function testLoadItem()
    {
        $this->test = new BitEvents($this->id);
        Assert::equals($this->test->load(), 23);
    }

    function testUrlItem()
    {
        Assert::equalsTrue($this->test->getDisplayUrl() != "");
    }

    function testExpungeItem()
    {
        Assert::equalsTrue($this->test->expunge());
    }

    function testCountItems()
    {
	$filter = array();
        $count = count($this->test->getList($filter));
        Assert::equals($this->count, $count);
    }

}
?>
