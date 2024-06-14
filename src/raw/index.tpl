<form action="/raw/save" class="form" method="POST" />
    <input type="hidden" name="submit" value="1">
    <input type="hidden" name="id" value="<?php echo $formData['id'] ?? 0?>">
    <ul>
        <li>
            <textarea placeholder="Текст" style="height: 200px" name="text"><?php echo $formData['text'] ?? ''?></textarea>
        </li>
        <li>
            <input type="text" placeholder="Источник" name="source" value="<?php echo $formData['source'] ?: ''?>" />
        </li>

        <li>
            <button type="submit">Сохранить <?php echo isset($formData['id']) ? 'изменения' : '' ?></button>
        </li>
    </ul>

</form>

<table class="table">
<?php foreach ($data as $d):?>
    <tr>
        <td><?=$d['text']?></td>
        <td><?=$d['source']?></td>
        <td><a href="/raw?id=<?=$d['id']?>" class="fa fa-edit"></a></td>
        <td><a href="/raw?delete=<?=$d['id']?>" class="fa fa-close"></a></td>
    </tr>
<?php endforeach;?>
</table>

