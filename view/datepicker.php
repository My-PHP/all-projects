<style type="text/css">
    .row {
    border: 1px solid #ccc;
    border-radius: 2px;
    padding: 10px;
    margin-bottom: 5px;
}
</style>

<div class="container"  ng-controller="Datepicker">
    <div id="demoForm" name="demoForm" ng-form="demoForm" novalidate>
        <div class="col-md-4">
            <label class="control-label">Available Date</label>
            <div class="input-form">
                <input type="text" class="form-control" uib-datepicker-popup="{{dateFormat}}" ng-model="AvailableDate" name="availabledate" is-open="availableDatePopup.opened" datepicker-options="availableDateOptions" ng-required="true" close-text="Close" ng-click="OpenAvailableDate()"
                ng-change="ChangeExpiryMinDate(AvailableDate)" placeholder="Available Date" />
                <label ng-show="showMessages && addJobForm.availabledate.$invalid" class="text-danger">
                    Available Date is required.
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <label class="control-label">Expire Date</label>
            <div class="input-form">
                <input type="text" class="form-control" uib-datepicker-popup="{{dateFormat}}" ng-model="ExpireDate" name="expiredate" is-open="expireDatePopup.opened" datepicker-options="expireDateOptions" ng-required="true" close-text="Close" ng-click="OpenExpireDate()"
                placeholder="Expire Date" />
                <label ng-show="showMessages && addJobForm.expiredate.$invalid" class="text-danger">
                    Expire Date is required.
                </label>
            </div>
        </div>
    </div>
</div>
