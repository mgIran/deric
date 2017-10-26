<?php
/* @var $this DashboardController*/
/* @var $devIDRequests CActiveDataProvider*/
/* @var $newestPrograms CActiveDataProvider*/
/* @var $newestDevelopers CActiveDataProvider*/
/* @var $newestPackages CActiveDataProvider*/
/* @var $updatedPackages CActiveDataProvider*/
/* @var $statistics []*/
/* @var $todaySales []*/
?>
<?php $this->renderPartial('//layouts/_flashMessage') ?>
<?php $this->renderPartial('_admin_dashboard', array(
    'newestPackages' => $newestPackages,
    'updatedPackages' => $updatedPackages,
    'newestPrograms' => $newestPrograms,
    'devIDRequests' => $devIDRequests,
    'newestDevelopers' => $newestDevelopers,
    'statistics' => $statistics,
    'todaySales' => $todaySales,
));