<div id="analytics-stats-erros-period" class="btn-group m-b-15 m-r-15">
    <button data-period="0 days" type="button" class="btn btn-default waves-effect">Today</button>
    <button data-period="1 weeks" type="button" class="btn btn-default waves-effect">Week</button>
    <button data-period="1 months" type="button" class="btn btn-default waves-effect">Month</button>
    <button data-period="3 months" type="button" class="btn btn-default waves-effect">Quarter</button>
    <button data-period="1 years" type="button" class="btn btn-default waves-effect">Year</button>
</div>
<div class="btn-group m-b-15">
    <button id="analytics-stats-calendar" type="button" class="btn btn-default waves-effect"><i class="zmdi zmdi-calendar"></i> <span id="analytics-stats-calendar-from">...</span> - <span id="analytics-stats-calendar-to">...</span></button>
</div>
<div id="analytics-stats-chart">
    <div class="text-center">
        <div class="preloader pl-xxl">
            <svg class="pl-circular" viewBox="25 25 50 50">
                <circle class="plc-path" cx="50" cy="50" r="20" />
            </svg>
        </div>
    </div>
</div>
<div id="analytics-stats-list"></div>
<input id="analytics-stat-date-from" type="hidden">
<input id="analytics-stat-date-to" type="hidden">