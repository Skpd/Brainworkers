<?php

namespace Brainworkers\Controller;

use Zend\Form\Element\DateTime;
use Zend\Mvc\Controller\AbstractActionController;
use Brainworkers\Entity\Payment;
use Brainworkers\Entity\PaymentLog;

/**
 * @method \Zend\Http\Request getRequest
 * @method \ZfcUser\Controller\Plugin\ZfcUserAuthentication zfcUserAuthentication
 * @package Brainworkers\Controller
 */
class PaymentController extends AbstractActionController
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    public function createAction()
    {
        $response = array('status' => false, 'message' => '', 'id' => null, 'baggage' => '');
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            /** @var $user \User\Entity\User */
            $user    = $this->zfcUserAuthentication()->getIdentity();
            $baggage = implode(',', $user->getTeams()->toArray());

            $payment = new Payment;
            $payment->setPayer($user);
            $payment->setBaggage($baggage);

            $this->getEntityManager()->persist($payment);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->refresh($payment);

            $response['status']  = true;
            $response['message'] = 'ok';
            $response['id']      = $payment->getId();
            $response['baggage'] = $baggage;
        } else {
            $response['message'] = 'access denied';
        }

        $view = $this->acceptableViewModelSelector(
            array('Zend\View\Model\ViewModel' => array('text/html'), 'Zend\View\Model\JsonModel' => array('application/json'))
        );

        $view->setVariables($response);

        return $view;
    }

    public function successAction()
    {

        $this->addLog('success');
        $params = $this->getRequest()->getPost()->toArray();

        /** @var $payment Payment */
        $payment = $this->getEntityManager()->find('Brainworkers\Entity\Payment', intval($params['ik_payment_id']));

        if (!empty($payment) && $this->checkSign($params)) {
            $payment->setAmount($params['ik_payment_amount']);
            $payment->setAlias($params['ik_paysystem_alias']);
            $payment->setTimestamp(new \DateTime('@' . $params['ik_payment_timestamp']));
            $payment->setState($params['ik_payment_state'] == 'success');
            $payment->setTransactionId($params['ik_trans_id']);
            $payment->setCurrencyRate($params['ik_currency_exch']);
            $payment->setFeesType(intval($params['ik_fees_payer']));
            $payment->setSign($params['ik_sign_hash']);

            foreach ($payment->getPayer()->getTeams() as $team) {
                $team->setPayed(true);
                $this->getEntityManager()->persist($team);
            }

            $this->getEntityManager()->persist($payment);
            $this->getEntityManager()->flush();
        }

        $this->flashMessenger()->addSuccessMessage('Payment Received');
        $this->redirect()->toRoute('home');
    }

    public function failAction()
    {
        $this->addLog('fail');
        $params = $this->getRequest()->getPost()->toArray();

        /** @var $payment Payment */
        $payment = $this->getEntityManager()->find('Brainworkers\Entity\Payment', intval($params['ik_payment_id']));

        if (!empty($payment) && $this->checkSign($params)) {
            $payment->setAmount($params['ik_payment_amount']);
            $payment->setAlias($params['ik_paysystem_alias']);
            $payment->setTimestamp(new \DateTime('@' . $params['ik_payment_timestamp']));
            $payment->setState($params['ik_payment_state'] == 'success');
            $payment->setTransactionId($params['ik_trans_id']);
            $payment->setCurrencyRate($params['ik_currency_exch']);
            $payment->setFeesType(intval($params['ik_fees_payer']));
            $payment->setSign($params['ik_sign_hash']);

            $this->getEntityManager()->persist($payment);
            $this->getEntityManager()->flush();
        }

        $this->flashMessenger()->addErrorMessage('Payment Failed');
        $this->redirect()->toRoute('home');
    }

    public function statusAction()
    {
        $params = $this->getRequest()->getPost()->toArray();

        /** @var $payment Payment */
        $payment = $this->getEntityManager()->find('Brainworkers\Entity\Payment', intval($params['ik_payment_id']));

        $this->addLog('status', $payment);

        if (!empty($payment) && $this->checkSign($params)) {
            $payment->setAmount(floatval($params['ik_payment_amount']));
            $payment->setAlias($params['ik_paysystem_alias']);
            $payment->setTimestamp(new \DateTime('@' . $params['ik_payment_timestamp']));
            $payment->setState($params['ik_payment_state'] == 'success');
            $payment->setTransactionId($params['ik_trans_id']);
            $payment->setCurrencyRate(floatval($params['ik_currency_exch']));
            $payment->setFeesType(intval($params['ik_fees_payer']));
            $payment->setSign($params['ik_sign_hash']);

            if ($payment->getState()) {
                foreach ($payment->getPayer()->getTeams() as $team) {
                    $team->setPayed(true);
                    $this->getEntityManager()->persist($team);
                }
            }

            $this->getEntityManager()->persist($payment);
            $this->getEntityManager()->flush();
        }

        return $this->getResponse();
    }

    private function checkSign(array $params)
    {
        $hash = strtoupper(
            md5(
                implode(
                    ':',
                    array(
                         $params['ik_shop_id'],
                         $params['ik_payment_amount'],
                         $params['ik_payment_id'],
                         $params['ik_paysystem_alias'],
                         $params['ik_baggage_fields'],
                         $params['ik_payment_state'],
                         $params['ik_trans_id'],
                         $params['ik_currency_exch'],
                         $params['ik_fees_payer'],
                         'Cr7QsiAWXJpwLxvU'
                    )
                )
            )
        );

        return $hash == $params['ik_sign_hash'];
    }

    private function addLog($type, $payment = null)
    {
        $paymentLog = new PaymentLog;
        $paymentLog->setType($type);

        if ($payment) {
            $paymentLog->setPayment($payment);
        }

        $params = array(
            'post'  => $this->getRequest()->getPost()->toArray(),
            'get'   => $this->getRequest()->getQuery()->toArray(),
            'route' => array(
                'name'   => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
                'params' => $this->getEvent()->getRouteMatch()->getParams(),
            )
        );

        $paymentLog->setParams(json_encode($params));

        $this->getEntityManager()->persist($paymentLog);
        $this->getEntityManager()->flush();
    }

    /** @return \Doctrine\ORM\EntityManager */
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->serviceLocator->get('doctrine.entity_manager.orm_default');
        }

        return $this->entityManager;
    }
}