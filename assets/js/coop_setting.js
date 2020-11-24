var _global_decimalPlaces, _global_places;

function _load(){

    if (typeof base_url === "undefined")  return;
    new Promise(resolve => {
        $.post(base_url+'coop_setting/get_setting', {}, function(res){
            resolve(res);
        })
    }).then((res) => {
        _global_places = res.setting.rounding_calc_period_interest;
        _global_decimalPlaces = res.setting.round_interest;
        console.log(this._global_decimalPlaces);
    });
}
_load();

function round(number, decimalPlaces){
    decimalPlaces = decimalPlaces === undefined ? _global_decimalPlaces : decimalPlaces;
    const factorOfTen = Math.pow(10, decimalPlaces);
    if(factorOfTen==0){
        return Math.round(number);
    }
    return Math.round(number * factorOfTen) / factorOfTen;
}

function ceil(number, decimalPlaces){
    decimalPlaces = decimalPlaces === undefined ? _global_decimalPlaces : decimalPlaces;
    return Math.ceil(number);
}

function floor(number, decimalPlaces){
    decimalPlaces = decimalPlaces === undefined ? _global_decimalPlaces : decimalPlaces;
    // const factorOfTen = Math.pow(10, decimalPlaces);
    return Math.floor(number);
}

//ปัดหลัก
function round_nearest(number, places, method){
    method = method === undefined ? 'round' :  method;
    places = places === undefined ? _global_places : places;
    const factorOfTen = Math.pow(10, places);
    if(factorOfTen===0){
        return number;
    }
    if(method.toLowerCase() === 'ceil') {
        return Math.ceil(number / factorOfTen) * factorOfTen;
    }else {
        return Math.round(number / factorOfTen) * factorOfTen;
    }
}

