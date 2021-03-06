<?php
/**
 * Template Name: Оплата
 * The template for displaying payment page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package UX_Mind_School
 */

get_header();
$page_queried_object = get_queried_object();
$page_id = $page_queried_object->ID;
//Page params
$is_promocode = get_field('promocode_bool', 2);
?>
<!-- Begin main -->
<main class="main template payment-page pages-template">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="breadcrumbs breadcrumbs_style-dark template__breadcrumbs">
					<?php bcn_display($return = false, $linked = true, $reverse = false, $force = false) ?>
				</div>
				<div class="pages-template__grid">
					<h1 class="title title_style-dark template__title payment-page__title">Оплатить курс</h1>
					<div class="row">
						<div class="col-12 col-lg-9">
							<div class="form payment-form">
								<section class="payment-form__section">
									<p class="payment-form__section-name">1. Выберите курс</p>
									<div class="payment-form__section-grid">
										<div class="form__input form__select payment-form__select payment-form__input">
											<?php
											$courses_array = array(
												'post_type'=>'post',
												'post_status'=>'publish',
												'cat'=>'15,1,2,4,5,99',
												'posts_per_page'=>-1,
												'meta_key'=>'ums_course_info_start',
												'orderby'=>'meta_value',
												'order'=>'ASC',
											);
											$courses_query = new WP_Query($courses_array); if ($courses_query->have_posts()): ?>
											<div data-type="payment" class="ums-select">
												<button data-price="0" data-sale-price="0" type="button" class="ums-select__btn">Нажмите, чтобы выбрать курс</button>
												<ul class="ums-select__list">
													<li data-payment-level="2" data-price="0" data-sale-price="0" class="ums-select__list-item">Оплата второго этапа действующего курса</li>
													<?php
														$counter = 0;
														while ( $courses_query->have_posts() ): $courses_query->the_post();
															$course_full_price = get_field('ums_course_info_price');
															$course_date = get_field('ums_course_info_start');
															$course_data_object = DateTime::createFromFormat('d/m/Y', $course_date);
															$course_date_string = $course_data_object->getTimestamp();
															$course_sale_price = get_field('ums_course_info_price_sale');
															$course_type = get_field( 'ums_course_info_type' );
															$is_partial_payment = get_field( 'is_partial_payment' );
													?>
													<li data-price="<?php echo $course_full_price; ?>" data-partial-payment="<?php if ( $is_partial_payment ): ?>yes<?php else: ?>no<?php endif; ?>" data-sale-price="<?php if($course_sale_price): echo $course_sale_price; else: echo $course_full_price; endif; ?>" class="ums-select__list-item"><?php echo date_i18n('j F', $course_date_string); ?> – <?php esc_html( the_title() ); ?><span> (<?php echo mb_strtolower( $course_type ); ?>)</span></li>
														<?php endwhile; ?>
												</ul>	
											</div>
											<?php endif; ?>
										</div>
									</div>
								</section>
								<section class="payment-form__section">
									<p class="payment-form__section-name">2. Выберите способ оплаты</p>
									<div class="payment-form__section-grid payment-form__section-options payment-methods"></div>
								</section>
								<div id="payment-anchor"></div>
								<section class="webpay-form payment-section payment-form__section payment-section_state-active">
									<p class="payment-form__section-name">3. Как оплатить через ЕРИП</p>
									<p class="webpay-form__note">После оплаты, отправьте, пожалуйста, копию квитанции на ящик <a href="mailto:hello@ux-school.by" class="link webpay-form__note-link">hello@ux-school.by</a>
									</p>
									<div class="erip__grid">
										<div class="erip__info">
											<p>Как найти нас в ЕРИП:</p>
											<ul>
												<li>Пункт Система «Расчет» (ЕРИП)</li>
												<li>Образование и развитие</li>
												<li>Дополнительное образование&nbsp;и&nbsp;развитие</li>
												<li>Тренинги, семинары, консультации</li>
												<li>Минск</li>
												<li>ИП Колесень И.Г.</li>
												<li>Посещение занятий</li>
												<li>Ввести ФИО ученика и&nbsp;сумму&nbsp;для&nbsp;оплаты</li>
											</ul>
										</div>
									</div>
								</section>
								<section class="payment-form__section payment-section">
									<div class="form webpay-form payment-form__section-item">
										<p class="payment-form__section-name">3. Введите ваши данные</p>
										<div class="payment-form__section-grid">
											<div class="webpay-form__item">
												<p class="ux-mind-payment__text"><!--При оплате в рассрочку скидки на курсы <span style="text-decoration: line-through;">не&nbsp;распространяются</span> действуют.--></p>
											</div>
											<div style="display: none;" class="webpay-form__item webpay-form__payment-level">
												<label class="radio webpay-form__radio">
													<input type="radio" checked class="radio__input" name="paymentLevel" value="1">
													<p class="radio__name">Оплата за первый этап</p>
												</label>
												<label class="radio webpay-form__radio">
													<input type="radio" class="radio__input" name="paymentLevel" value="2">
													<p class="radio__name">Оплата за второй этап</p>
												</label>
											</div>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input">
													<input type="text" inputmode="text" required name="name">
													<span class="form__label">Имя и фамилия ученика</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
												</div>
												<label class="checkbox webpay-form__sale-checkbox">
													<input type="checkbox" name="sale" class="checkbox__input">
													<p class="checkbox__name">Я студент-очник / я раньше уже учился у вас (скидка 10%)</p>
												</label>
											</div>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input currency-input-wrapper">
													<input type="text" required name="total" value="">
													<span class="form__label">Сумма для оплаты</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
													<div class="ums-currency form__ums-currency"></div>
												</div>
											</div>
											<p class="webpay-form__note">После оплаты, отправьте, пожалуйста, копию квитанции на ящик <a href="mailto:hello@ux-school.by" class="link webpay-form__note-link">hello@ux-school.by</a><br><br>✴ Второй этап оплаты через 1 месяц, после первого платежа</p>
											<button type="button" data-payment-method="alfa" class="btn webpay-form__btn webpay-form__btn-ajax">Перейти к оплате</button>
										</div>
									</form>
								</section>
								<section class="payment-form__section payment-section">
									<div class="form webpay-form payment-form__section-item">
										<p class="payment-form__section-name">3. Введите ваши данные</p>
										<div class="payment-form__section-grid">
											<div class="webpay-form__item">
												<p class="halva-payment__text">Халва MIX рассрочка 2 мес.<br>Халва MAX рассрочка 2 мес. + 2 мес. от Банка = 4 мес. (с возможностью продления до 9 мес.)<br>Подробности на сайте <a class="link" href="https://www.mtbank.by/private/cards/mixmax-cards" rel="noopener noreferrer" target="_blank">МТБанка</a></p>
											</div>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input">
													<input type="text" required name="wsb_customer_name">
													<span class="form__label">Имя и фамилия ученика</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
												</div>
											</div>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input currency-input-wrapper">
													<input type="text" readonly required name="wsb_total" value="">
													<span class="form__label">Сумма для оплаты</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
													<div class="ums-currency form__ums-currency"></div>
												</div>
											</div>
											<p class="webpay-form__note">После оплаты, отправьте, пожалуйста, копию квитанции на ящик <a href="mailto:hello@ux-school.by" class="link webpay-form__note-link">hello@ux-school.by</a></p>
											<button data-payment-method="bepaid" class="btn webpay-form__btn webpay-form__btn-ajax">Перейти к оплате</button>
										</div>
									</form>
								</section>
								<section class="payment-form__section payment-section bank-payment">
									<p class="payment-form__section-name">3. Как оплатить в отделении банка</p>
									<ul class="bank-payment__desc">
										<li>Передайте операционисту в банке реквизиты нашей компании (ниже)</li>
										<li>Укажите наименование услуги «Оплата за обучающие курсы»</li>
										<li>Назовите сумму оплаты и ваше ФИО</li>
										<li>После оплаты, отправьте пожалуйста копию квитанции на ящик <a class="link" href="mailto:hello@ux-school.by">hello@ux-school.by</a></li>
									</ul>
									<p class="payment-form__section-name">Реквизиты для оплаты:</p>
									<p class="bank-payment__content">Индивидуальный предприниматель Колесень Игорь Геннадьевич</br>УНП 190602238</br>Р/С BY03ALFA30132172600080270000</br>в ЗАО «АЛЬФА-БАНК» БИК ALFABY2X г. Минск, пр-т Независимости 177</p>
								</section>
								<section class="payment-form__section payment-section">
									<div class="form webpay-form payment-form__section-item">
										<p class="payment-form__section-name">3. Введите ваши данные</p>
										<div class="payment-form__section-grid">
											<p class="webpay-form__note">Свяжитесь с нами и мы предложим вам удобный вариант оплаты картой</p>
											<div class="card-payment-info webpay-form__info">
												<a href="tel:+375298630657" class="card-payment-info__link">
													+375 (29) 863-06-57<br/>
													<span class="card-payment-info__social-name">Telegram,</span><span class="card-payment-info__social-name">WhatsApp,</span><span class="card-payment-info__social-name">Viber</spam>
												</a>
												<a class="card-payment-info__link" href="mailto:hello@ux-school.by">hello@ux-school.by</a>
											</div>
											<?php if ( is_user_logged_in() ): ?>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input">
													<input type="text" inputmode="text" required name="name">
													<span class="form__label">Имя и фамилия ученика</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
												</div>
												<label class="checkbox webpay-form__sale-checkbox">
													<input type="checkbox" name="sale" class="checkbox__input">
													<p class="checkbox__name">Я студент-очник / Учился у вас (скидка 10%)</p>
												</label>
											</div>
											<div class="webpay-form__item">
												<div class="form__input payment-form__input currency-input-wrapper">
													<input type="text" required name="total" value="">
													<span class="form__label">Сумма для оплаты</span>
													<span role="alert" class="form__error-label">Поле обязательно для заполнения</span>
													<div class="ums-currency form__ums-currency"></div>
												</div>
											</div>
											<?php if ($is_promocode): ?>
											<div class="webpay-form__item promocode">
												<label class="toggle-checkbox">
													<input type="checkbox" name="promocode-toggle" class="toggle-checkbox__input">
													<div class="toggle-checkbox__element"></div>
													<p class="toggle-checkbox__name">У меня есть промо-код</p>
												</label>
												<div class="form__input promocode-input payment-form__input">
													<input type="text" inputmode="text" name="promocode">
													<span class="form__label">Промо-код</span>
													<span role="alert" class="form__error-label">Недействительный промокод</span>
													<button type="button" class="promocode-input__btn">Применить</button>
												</div>
											</div>
											<?php endif; ?>
											<p class="webpay-form__note">После оплаты, отправьте, пожалуйста, копию квитанции на ящик <a href="mailto:hello@ux-school.by" class="link webpay-form__note-link">hello@ux-school.by</a></p>
											<button type="button" data-payment-method="alfa" class="btn webpay-form__btn webpay-form__btn-ajax">Перейти к оплате</button>
											<?php endif; ?>
										</div>
									</form>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<!-- End main -->
<!-- Begin templates -->
<template id="payment-method">
	<label class="payment-item payment-form__method">
		<input type="radio" name="payment" class="payment-item__input"/>
		<p class="payment-item__name"></p>
    </label>
</template>
<!-- End template -->
<?php get_footer();