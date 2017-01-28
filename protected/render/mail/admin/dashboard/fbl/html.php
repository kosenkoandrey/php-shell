<div id="fbl-reports-period" class="btn-group m-b-15 m-r-15">
    <button data-period="0 days" type="button" class="btn btn-default waves-effect">Сегодня</button>
    <button data-period="1 weeks" type="button" class="btn btn-default waves-effect">Неделя</button>
    <button data-period="1 months" type="button" class="btn btn-default waves-effect">Месяц</button>
    <button data-period="3 months" type="button" class="btn btn-default waves-effect">Квартал</button>
    <button data-period="1 years" type="button" class="btn btn-default waves-effect">Год</button>
</div>
<div class="btn-group m-b-15">
    <button id="fbl-reports-calendar" type="button" class="btn btn-default waves-effect"><i class="zmdi zmdi-calendar"></i> <span id="fbl-reports-calendar-from">...</span> - <span id="fbl-reports-calendar-to">...</span></button>
</div>
<div id="fbl-log-chart">
    <div class="text-center">
        <div class="preloader pl-xxl">
            <svg class="pl-circular" viewBox="25 25 50 50">
                <circle class="plc-path" cx="50" cy="50" r="20" />
            </svg>
        </div>
    </div>
</div>
<input id="fbl-log-date-from" type="hidden">
<input id="fbl-log-date-to" type="hidden">