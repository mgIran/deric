<?php/* @var $data AppDiscounts */$app = $data->app;?><tr>    <td><a target="_blank" href="<?= $app->getViewUrl() ?>"><?php echo $app->title;?></a></td>    <td><?php echo ($app->status=='enable')?'فعال':'غیر فعال';?></td>    <td><?php        if($app->price==0)            echo 'رایگان';        elseif($app->price==-1)            echo 'پرداخت درون برنامه';        else            echo Controller::parseNumbers(number_format($app->price,0)).' تومان';        ?></td>    <td>        <?= Controller::parseNumbers($data->percent).'%' ?>    </td>    <td>        <?= Controller::parseNumbers(number_format($app->offPrice)).' تومان' ?>    </td>    <td>        <?        echo Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->start_date));        echo '<br>الی<br>';        echo Controller::parseNumbers(JalaliDate::date('Y/m/d - H:i',$data->end_date));        ?>    </td></tr>