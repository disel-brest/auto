<?php

namespace app\modules\main\widgets;


use yii\base\Widget;

class UrgentSaleWidget extends Widget
{
    public function run()
    {
        ?>
        <div class="others-proposals-container">
            <div class="proposals-left">
                <p>Срочно продаю</p>
                <p>без торга</p>
                <div class="proposal-btn">
                    <a href="#">Срочно продать</a>
                </div>
            </div>
            <div class="other-proposals">
                <div class="other-proposal">
                    <a href="#">
                        <div class="other-proposal-img">
                            <img src="/images/car-1.jpg" alt="">
                        </div>
                        <div class="price">16 500$</div>
                        <div class="other-proposal-title">Infinity FX35</div>
                    </a>
                </div>

                <div class="other-proposal">
                    <a href="#">
                        <div class="other-proposal-img">
                            <img src="/images/car-1.jpg" alt="">
                        </div>
                        <div class="price">16 500$</div>
                        <div class="other-proposal-title">Mercedes Benz C-klasse (w205)</div>
                    </a>
                </div>

                <div class="other-proposal">
                    <a href="#">
                        <div class="other-proposal-img">
                            <img src="/images/car-1.jpg" alt="">
                        </div>
                        <div class="price">16 500$</div>
                        <div class="other-proposal-title">BMW 525 (E39)</div>
                    </a>
                </div>

                <div class="other-proposal">
                    <a href="#">
                        <div class="other-proposal-img">
                            <img src="/images/car-1.jpg" alt="">
                        </div>
                        <div class="price">16 500$</div>
                        <div class="other-proposal-title">Volvo S90</div>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}