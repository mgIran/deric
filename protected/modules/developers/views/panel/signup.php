<?php
/* @var $this PanelController */
/* @var $step String */
/* @var $data Array */
?>

<div class="developer-signup-container">
    <div class="col-md-8 col-md-offset-2">
        <h2 class="text-center">توسعه دهنده شوید</h2>
        <div class="steps-container">
            <ul class="steps">
                <li class="col-md-4<?php if($step=='agreement'):?> active<?php endif;?>">
                    <span><span class="num">1</span>توافق نامه</span>
                    <div class="arrow"><div></div></div>
                </li>
                <li class="col-md-4<?php if($step=='profile'):?> active<?php endif;?>">
                    <span><span class="num">2</span>اطلاعات قرارداد</span>
                    <div class="arrow"><div></div></div>
                </li>
                <li class="col-md-4<?php if($step=='finish'):?> active<?php endif;?>">
                    <span><span class="num">3</span>اتمام</span>
                </li>
            </ul>
            <div class="step-content"></div>
                <?php switch($step){
                    case 'agreement':
                        $this->renderPartial('_agreement', array(
                            'text'=>$data['agreementText']['summary']
                        ));
                        break;
                    case 'profile':
                        $this->renderPartial('_profile', array(
                            'model'=>$data['detailsModel'],
                            'nationalCardImage'=>$data['nationalCardImage'],
                            'registrationCertificateImage'=>$data['registrationCertificateImage'],
                        ));
                        break;
                    case 'finish':
                        $this->renderPartial('_finish', array(
                            'model'=>$data['userDetails'],
                        ));
                        break;
                }?>
            </div>
        </div>
    </div>
</div>