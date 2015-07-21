/* 
 * Copyright 2015 Patryk Wychowaniec.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
$('#buttonPrint').click(function() {
    window.print();
});

//lines modal
$('.lines-show').on('click',function() {
    $('#linesModal').modal();
});

//settings modal
$('.settings-show').on('click',function() {
    $('#settingsModal').modal();
});

function getCurrentStyle() {
	return Cookies.get('style');
}

function getCurrentLanguage() {
	return Cookies.get('lang');
}

$(document).ready(function() {
	$('.btn-lang').on('click', function() {
        Cookies.set('lang',$(this).data('lang'));
        document.location.reload();
    });

    $('.btn-style').on('click', function() {
        Cookies.set('style',$(this).data('style'));
        document.location.reload();
    });
});