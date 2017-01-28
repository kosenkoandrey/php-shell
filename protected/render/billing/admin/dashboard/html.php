<div id="billing-period" class="btn-group m-b-15 m-r-15">
    <button data-period="0 days" type="button" class="btn btn-default waves-effect">Сегодня</button>
    <button data-period="1 weeks" type="button" class="btn btn-default waves-effect">Неделя</button>
    <button data-period="1 months" type="button" class="btn btn-default waves-effect">Месяц</button>
    <button data-period="3 months" type="button" class="btn btn-default waves-effect">Квартал</button>
    <button data-period="1 years" type="button" class="btn btn-default waves-effect">Год</button>
</div>
<div class="btn-group m-b-15">
    <button id="billing-calendar" type="button" class="btn btn-default waves-effect"><i class="zmdi zmdi-calendar"></i> <span id="billing-calendar-from">...</span> - <span id="billing-calendar-to">...</span></button>
</div>
<div id="billing-chart">
    <div class="text-center">
        <div class="preloader pl-xxl">
            <svg class="pl-circular" viewBox="25 25 50 50">
                <circle class="plc-path" cx="50" cy="50" r="20" />
            </svg>
        </div>
    </div>
</div>
<input id="billing-date-from" type="hidden">
<input id="billing-date-to" type="hidden">
<div id="billing-list"></div>