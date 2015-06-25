/* 
 * Copyright 2015 Patryk.
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

(function() {
    
    var $tripModalBody = $('#tripModalBody'),
        $tripModal = $('#tripModal');
        
    function getTrip(line,dir_no,stop_id,daytype_id,trip_no) {
        var url = URL+'/api/trip/';
        url += line+'/'+dir_no+'/'+stop_id+'/'+daytype_id+'/'+trip_no;
        $.ajax({
            url: url,
            method: 'GET'
        })
        .done(function(response) {
            $tripModalBody.html(response);
        })
        .error(function() {
            $tripModalBody.html('Wystąpił błąd');
        });
    }
    
    $('.trip-show').click(function() {
       var $self = $(this),
           line = $self.attr('data-line'),
           dir_no = $self.attr('data-dir-no'),
           stop_id = $self.attr('data-stop-id'),
           daytype_id = $self.attr('data-daytype-id'),
           trip_no = $self.attr('data-trip-no');
       
       $tripModalBody.html('<img src="'+URL+'/img/ajax-loader.gif" alt="loading">');
       $tripModal.modal();
       getTrip(line,dir_no,stop_id,daytype_id,trip_no);
    });
    
})();