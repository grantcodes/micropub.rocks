<?php $this->layout('layout', [
                      'title' => $test->name,
                    ]); ?>

<div class="single-column">

  <section class="content">
    <h2><?= e($test->number . ': ' . $test->name) ?></h2>

    <p>This is a basic test of creating an h-entry post at your Micropub endpoint in JSON format.</p>
    <p>Clicking "Run" will make the following request to your endpoint.</p>
  </section>

  <section class="content code">
    <pre>POST <?= $endpoint->micropub_endpoint ?> HTTP/1.1
Authorization: Bearer <?= $endpoint->access_token."\n" ?>
Content-type: application/json

<span id="postbody">{
  "type": ["h-entry"],
  "properties": {
    "content": ["Micropub test of creating an h-entry with a JSON request"]
  }
}</span></pre>
  </section>

  <section class="content">
    <button class="ui green button" id="run">Run</button>
    <ul class="result-list">
      <li><span id="passed_code" class="ui circular label">&nbsp;</span> Returned HTTP <code>201</code> or <code>202</code></li>
      <li><span id="passed_location" class="ui circular label">&nbsp;</span> Returned a <code>Location</code> header <span id="location_header_value"></span></li>
    </ul>
  </section>

  <section class="content hidden" id="response-section">
    <h3>Response</h3>
    <pre id="response" class="small"></pre>
  </section>

</div>
<script>
var test = <?= $test->id ?>;
var endpoint = <?= $endpoint->id ?>;

set_up_json_test(test, endpoint, function(data){
  var passed_code = false;
  var passed_location = false;
  if(data.code == 201 || data.code == 202) {
    passed_code = true;
  }
  set_result_icon("#passed_code", passed_code ? 1 : -1);
  if(data.location) {
    passed_location = true;
    $("#location_header_value").html('<a href="'+data.location+'" target="_blank">view post</a>');
  }
  set_result_icon("#passed_location", passed_location ? 1 : -1);
  store_result(test, endpoint, (passed_code && passed_location ? 1 : -1));

  // Created a post in JSON syntax
  store_server_feature(endpoint, 6, (passed_code && passed_location ? 1 : -1), test);
  if(passed_code && passed_location) {
    // Returned HTTP 201 or 202
    store_server_feature(endpoint, (data.code == 201 ? 14 : 15), 1, test);
  }

});

</script>
