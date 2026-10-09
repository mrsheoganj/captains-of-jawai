<?php
/**
 * Renders one form field.
 * Vars: $name, $label, $type, $opt (array), $value, $error, $lists
 */
use App\Core\Media;

$opt ??= [];
$id = 'f_' . $name;
$req = !empty($opt['required']) ? ' required' : '';
$ph = isset($opt['placeholder']) ? ' placeholder="' . e($opt['placeholder']) . '"' : '';
$wrapClass = 'field field-' . $type . (!empty($opt['width']) ? ' field-' . $opt['width'] : '') . (!empty($error) ? ' has-error' : '');
?>
<div class="<?= e($wrapClass) ?>">
  <?php if ($type === 'bool'): ?>
    <label class="switch"><input type="checkbox" name="<?= e($name) ?>" value="1"<?= !empty($value) && $value !== '0' ? ' checked' : '' ?>><span class="switch-ui"></span><span><?= e($label) ?></span></label>
  <?php else: ?>
    <label for="<?= e($id) ?>"><?= e($label) ?><?= $req ? ' <span class="req">*</span>' : '' ?></label>
    <?php switch ($type):
      case 'textarea': case 'lines': case 'code': ?>
        <textarea id="<?= e($id) ?>" name="<?= e($name) ?>" rows="<?= (int) ($opt['rows'] ?? ($type === 'code' ? 6 : 4)) ?>"<?= $req . $ph ?><?= $type === 'code' ? ' class="code" spellcheck="false"' : '' ?>><?= e($value) ?></textarea>
        <?php break;
      case 'rich': ?>
        <div class="rte" data-rte>
          <div class="rte-bar">
            <select data-cmd="formatBlock" aria-label="Paragraph style"><option value="p">Paragraph</option><option value="h2">Heading 2</option><option value="h3">Heading 3</option><option value="blockquote">Quote</option></select>
            <button type="button" data-cmd="bold" title="Bold"><b>B</b></button>
            <button type="button" data-cmd="italic" title="Italic"><i>I</i></button>
            <button type="button" data-cmd="insertUnorderedList" title="Bullet list">• List</button>
            <button type="button" data-cmd="insertOrderedList" title="Numbered list">1. List</button>
            <button type="button" data-cmd="createLink" title="Link"><?= icon('link', 14) ?></button>
            <button type="button" data-cmd="unlink" title="Remove link">Unlink</button>
            <button type="button" data-cmd="insertImage" title="Insert image"><?= icon('image', 14) ?></button>
            <button type="button" data-cmd="insertHorizontalRule" title="Divider">―</button>
            <button type="button" data-cmd="removeFormat" title="Clear formatting">Tx</button>
            <button type="button" data-cmd="source" title="Edit HTML" class="rte-src">HTML</button>
          </div>
          <div class="rte-area prose-admin" contenteditable="true" data-rte-area></div>
          <textarea id="<?= e($id) ?>" name="<?= e($name) ?>" class="rte-source code" hidden data-rte-source><?= e($value) ?></textarea>
        </div>
        <?php break;
      case 'select': case 'select_kv': ?>
        <select id="<?= e($id) ?>" name="<?= e($name) ?>"<?= $req ?>><?php foreach ($opt['choices'] ?? [] as $k => $l): ?><option value="<?= e($k) ?>"<?= (string) $value === (string) $k ? ' selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select>
        <?php break;
      case 'image': $m = $value ? Media::find((int) $value) : null; ?>
        <div class="image-field" data-image-field>
          <input type="hidden" name="<?= e($name) ?>" value="<?= e($value) ?>" data-image-input>
          <div class="image-preview" data-image-preview><?php if ($m): ?><img src="<?= e(Media::url($m, 'md')) ?>" alt=""><?php else: ?><span><?= icon('image', 22) ?> No image</span><?php endif; ?></div>
          <div class="image-actions"><button type="button" class="btn btn-light btn-sm" data-image-pick>Choose…</button><button type="button" class="btn btn-text btn-sm" data-image-clear<?= $m ? '' : ' hidden' ?>>Remove</button></div>
        </div>
        <?php break;
      case 'images': $ids = array_filter(array_map('intval', explode(',', (string) $value))); ?>
        <div class="images-field" data-images-field>
          <input type="hidden" name="<?= e($name) ?>" value="<?= e(implode(',', $ids)) ?>" data-images-input>
          <div class="images-list" data-images-list>
            <?php foreach ($ids as $mid): if (!($m = Media::find($mid))) continue; ?><figure data-id="<?= $mid ?>"><img src="<?= e(Media::url($m, 'sm')) ?>" alt=""><button type="button" data-remove aria-label="Remove">×</button></figure><?php endforeach; ?>
          </div>
          <button type="button" class="btn btn-light btn-sm" data-images-add><?= icon('plus', 14) ?> Add image</button>
        </div>
        <?php break;
      case 'slug': ?>
        <div class="input-prefix"><span><?= e($opt['prefix'] ?? '/') ?></span><input id="<?= e($id) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" data-slug="<?= e($opt['from'] ?? 'title') ?>" placeholder="auto-generated"></div>
        <?php break;
      case 'datetime': ?>
        <input type="datetime-local" id="<?= e($id) ?>" name="<?= e($name) ?>" value="<?= e($value ? date('Y-m-d\TH:i', strtotime((string) $value)) : '') ?>"<?= $req ?>>
        <?php break;
      case 'number': ?>
        <input type="number" id="<?= e($id) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>"<?= $req . $ph ?>>
        <?php break;
      case 'color': ?>
        <div class="color-field"><input type="color" value="<?= e($value) ?>" data-color-sync="<?= e($id) ?>" aria-label="<?= e($label) ?>"><input id="<?= e($id) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" pattern="#[0-9A-Fa-f]{6}" maxlength="7"></div>
        <?php break;
      case 'secret': ?>
        <input type="password" id="<?= e($id) ?>" name="<?= e($name) ?>" value="" autocomplete="new-password" placeholder="<?= !empty($opt['has']) ? '•••••••• (saved — leave blank to keep)' : 'Not set' ?>">
        <?php if (!empty($opt['has'])): ?><label class="check-inline"><input type="checkbox" name="<?= e($name) ?>_clear" value="1"> Clear saved password</label><?php endif; ?>
        <?php break;
      default: ?>
        <input type="<?= in_array($type, ['email', 'url'], true) ? $type : 'text' ?>" id="<?= e($id) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>"<?= $req . $ph ?><?= !empty($opt['list']) ? ' list="dl_' . e($opt['list']) . '"' : '' ?><?= !empty($opt['seo']) ? ' data-seo="' . e($opt['seo']) . '"' : '' ?>>
        <?php if (!empty($opt['list']) && !empty($lists[$opt['list']])): ?><datalist id="dl_<?= e($opt['list']) ?>"><?php foreach ($lists[$opt['list']] as $o): ?><option value="<?= e($o) ?>"><?php endforeach; ?></datalist><?php endif; ?>
    <?php endswitch; ?>
  <?php endif; ?>
  <?php if (!empty($opt['help'])): ?><small class="help"><?= e($opt['help']) ?></small><?php endif; ?>
  <?php if (!empty($error)): ?><em class="err"><?= e($error) ?></em><?php endif; ?>
</div>
