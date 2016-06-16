<?php

/**
 * Class MP_Debug_Test_Model_Core_Email
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Model_Core_Email
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Core_Email extends EcomDev_PHPUnit_Test_Case
{

    public function testRewrite()
    {
        $model = Mage::getModel('core/email');
        $this->assertNotNull($model);
        $this->assertInstanceOf('Mage_Core_Model_Email', $model);
        $this->assertInstanceOf('MP_Debug_Model_Core_Email', $model);
    }


    /**
     * @covers MP_Debug_Model_Core_Email_Capture::send
     */
    public function testSend()
    {
        $model = $this->getModelMock('mp_debug/core_email', array('parentSend', 'captureEmail'));
        $model->expects($this->once())->method('parentSend')->willReturnSelf();
        $model->expects($this->once())->method('captureEmail');

        $model->send();
    }


    /**
     * @covers MP_Debug_Model_Core_Email_Capture::captureEmail
     */
    public function testCaptureEmail()
    {
        $model = $this->getModelMock('mp_debug/core_email', array(
            'getFromEmail', 'getFromName', 'getToEmail', 'getToName', 'getSubject', 'getType', 'getBody'
        ));
        $model->expects($this->any())->method('getFromEmail')->willReturn('mario+sender@mailinator.com');
        $model->expects($this->any())->method('getFromName')->willReturn('Mario Sender');
        $model->expects($this->any())->method('getToEmail')->willReturn('mario+receiver@mailinator.com');
        $model->expects($this->any())->method('getToName')->willReturn('Mario Receiver');
        $model->expects($this->any())->method('getSubject')->willReturn('E-mail subject');
        $model->expects($this->any())->method('getType')->willReturn('html');
        $model->expects($this->any())->method('getBody')->willReturn('e-mail body');

        $emailMock = $this->getModelMock('mp_debug/email',
            array('setFromName', 'setFromEmail', 'setToEmail', 'setToName', 'setSubject', 'setIsPlain', 'setBody', 'setIsAccepted', 'setVariables'));
        $this->replaceByMock('model', 'mp_debug/email', $emailMock);
        $emailMock->expects($this->once())->method('setFromName')->with('Mario Sender');
        $emailMock->expects($this->once())->method('setFromEmail')->with('mario+sender@mailinator.com');
        $emailMock->expects($this->once())->method('setToEmail')->with('mario+receiver@mailinator.com');
        $emailMock->expects($this->once())->method('setToName')->with('Mario Receiver');
        $emailMock->expects($this->once())->method('setSubject')->with('E-mail subject');
        $emailMock->expects($this->once())->method('setIsPlain')->with(false);
        $emailMock->expects($this->once())->method('setBody')->with('e-mail body');
        $emailMock->expects($this->once())->method('setIsAccepted')->with(true);
        $emailMock->expects($this->never())->method('setVariables');

        $requestInfo = $this->getModelMock('mp_debug/requestInfo', array('addEmail'));
        $requestInfo->expects($this->once())->method('addEmail')->with($emailMock);

        $observer = $this->getModelMock('mp_debug/observer', array('getRequestInfo'));
        $this->replaceByMock('singleton', 'mp_debug/observer', $observer);
        $observer->expects($this->any())->method('getRequestInfo')->willReturn($requestInfo);

        $model->captureEmail();
    }

}
