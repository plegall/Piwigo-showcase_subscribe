{literal}
<style>
form fieldset p {text-align:left;margin:0 0 1.5em 0;line-height:20px;}
form fieldset input[type="text"] {width:400px}
form fieldset textarea {width:600px;height:100px}
.fieldCaption {font-style:italic;font-size:90% }
.permissionActions {text-align:center;height:20px}
.permissionActions a:hover {border:none}
.permissionActions img {margin-bottom:-2px}
</style>
{/literal}


<div class="titrePage">
  <h2>Showcase Subscribe</h2>
</div>

{if $CURRENT_STATUS eq 'unknown'}
<form method="post" name="subscribeToShowcase" action="{$F_ACTION}" class="properties">
  <fieldset>
    <legend>{'Subscribe my gallery to Piwigo Showcase'|@translate}</legend>

    <p style="margin-top:10px">
      <strong>{'Gallery Address (URL)'|@translate}</strong>
      <br>
      {$URL}
    </p>

    <p>
      <strong>{'Creation date'|@translate}</strong>
      <br>
      {$DATE_CREATION}
    </p>

    <p>
      <strong>{'Email address'|@translate}</strong> <span class="fieldCaption">{'(optional)'|@translate}</span>
      <br>
      <input type="text" name="email" value="{$EMAIL}">
      <br><span class="fieldCaption">{'provide it if you want to be notified when your gallery is registered'|@translate}</span>
    </p>

    <p>
      <strong>{'Gallery title'|@translate}</strong> <span class="fieldCaption">{'(optional)'|@translate}</span>
      <br>
      <input type="text" name="title" value="{$TITLE}">
    </p>

    <p>
      <strong>{'Author'|@translate}</strong> <span class="fieldCaption">{'(optional)'|@translate}</span>
      <br>
      <input type="text" name="author" value="{$AUTHOR}">
    </p>

    <p>
      <strong>{'Tags'|@translate}</strong> <span class="fieldCaption">{'(optional)'|@translate}</span>
      <br>
      <input type="text" name="tags" value="{$TAGS}">
      <br><span class="fieldCaption">{'(example: nature, landscape, portrait)'|@translate}</span>
    </p>

    <p>
      <strong>{'Description'|@translate}</strong> <span class="fieldCaption">{'(optional)'|@translate}</span>
      <br>
      <textarea name="description">{$DESCRIPTION}</textarea>
      <br><span class="fieldCaption">{'A good description will improve your visibility in search engines'|@translate}</span>
    </p>

    <p style="margin:0;">
      <input class="submit" type="submit" name="submit_subscribe" value="{'Subscribe'|@translate}"/>
    </p>
  </fieldset>
</form>
{/if}