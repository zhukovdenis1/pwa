<script type="text/javascript" src="/js/jquery.dropdown.js?2"></script>
<script type="text/javascript" src="/js/jquery.inputdropdown.js?2"></script>
<link href="/css/dropdown.css" rel="stylesheet" type="text/css" />
<form action="/english/save" class="form" method="POST" />
    <input type="hidden" name="submit" value="1">
    <input type="hidden" name="id" value="<?php echo $formData['id'] ?? 0?>">
    <input type="hidden" name="category_id" value="<?php echo $category['id'] ?? 0?>">
    <input type="hidden" id="tags" name="tags" value="<?php echo $formData['tags'] ?? ''?>">
    <ul>
        <li>
            <input type="hidden" name="important" value="0">
            <!--input style="float: right" type="checkbox" name="important" value="1" <?php echo (isset($formData['important']) && $formData['important'] ) ? 'checked="checked"' : ''  ?>-->
            <textarea maxlength="1000" placeholder="<?php echo $cfg['forward'] ?? 'Слово'?>" name="forward"><?php echo $formData['forward'] ?? ''?></textarea>
        </li>
        <li>
            <textarea  maxlength="1000" placeholder="<?php echo $cfg['backward'] ?? ''?>" name="backward"><?php echo $formData['backward'] ?? ''?></textarea>
        </li>
        <li>
            <input type="text" placeholder="Источник" name="source_id" id="source" value="<?php echo $formData['source_id']?>" />
        </li>
        <li>
            <textarea placeholder="Дополнительная информация" name="description"><?php echo $formData['description'] ?? ''?></textarea>
        </li>
        <li>
            <input type="text" placeholder="Сессия" name="session_id" id="session" value="<?php echo $formData['session_id']?>" />
        </li>
        <li>
            <input maxlength="128" type="text" placeholder="Подсказка" name="help" value="<?php echo $formData['help'] ?? ''?>" />
        </li>
        <li>
            <ul class="tags" id="tagsBox">
                <?php foreach ($tags as $tag):?>
                    <li id="<?php echo $tag['id']?>" class="<?php echo $tag['selected'] ? 'selected' : ''?>"><?php echo $tag['name'];?></li>
                <?php endforeach;?>
            </ul>
        </li>
        <li>
            <button type="submit">Сохранить <?php echo isset($formData['id']) ? 'изменения' : '' ?></button>
        </li>
    </ul>

</form>

<?php if (!$wordId):?>
<table class="table">
<?php foreach ($data as $d):?>
    <tr>
        <td><?=$d['forward']?></td>
        <td><?=$d['backward']?></td>
        <td><a href="/english?id=<?=$d['id']?>" class="fa fa-edit"></a></td>
    </tr>
<?php endforeach;?>
</table>
<?php endif;?>

<script type="text/javascript">
    $('#tagsBox li').click(function() {
        let id = $(this).attr('id');
        $(this).toggleClass('selected');
        let allId = [];
        $('#tagsBox li.selected').each(function() {
            allId.push($(this).attr('id'));
        });
        $('#tags').val(allId);
    });

    $('#source').inputDropDown({
        getUrl: '/source/ajax-find?category_id=<?php echo $category['id'] ?? 0?>',
        addUrl: '/source/ajax-save?category_id=<?php echo $category['id'] ?? 0?>',
        defaultValue: '<?php echo $formData['source_name'] ?? ''?>'
    });

    $('#session').inputDropDown({
        getUrl: '/session/ajax-find?category_id=<?php echo $category['id'] ?? 0?>',
        addUrl: '/session/ajax-save?category_id=<?php echo $category['id'] ?? 0?>',
        defaultValue: '<?php echo $formData['session_name'] ?? ''?>'
    });
</script>
