<?php if ($word) :?>
    <div class="remind">
        <a href="/english?id=<?php echo $word['id']?>" class="fa fa-2x fa-edit" style="text-decoration: none;color: gray;float: right"></a>
        <div class="tags">
            <b><?php echo $category['name']?></b>
            <?php foreach ($tags as $tag) :?>
                <span><?php echo $tag?></span>
            <?php endforeach;?>
        </div>
        <div class="forward word <?php echo ($direction == 'forward') ? '' : 'word-hide'?>"><pre><?php echo $word['forward']?></pre></div>
        <div class="backward word <?php echo ($direction == 'backward') ? '' : 'word-hide'?>"><pre><?php echo $word['backward']?></pre></div>
        <?php if ($word['help']):?>
            <div class="word">
                <pre><b>Посказка:</b> <?php echo $word['help']?></pre>
            </div>
        <?php endif;?>
    </div>

    <form action="" class="form" method="POST" style="display: none">
        <input type="hidden" name="submit" value="1" />
        <input type="hidden" name="word_id" value="<?php echo $word['id']?>">
        <input type="hidden" name="direction" value="<?php echo $direction?>">
        <label>ДА<input type="checkbox" name="done" value="5"></label>

        <label style="float: right"><input type="checkbox" name="done" value="0">НЕТ</label>
        <button style="margin: 20px 0" type="submit">Далее</button>
        <input maxlength="128"  type="text" name="help" value="<?php echo $word['help']?>" placeholder="Посказка" style="border: 1px solid #eee" />

        <?php if ($word['source_name']):?>
        <div class="word">
            <pre><b>Источник:</b> <?php echo $word['source_name']?></pre>
        </div>
        <?php endif;?>

        <?php if ($word['session_name']):?>
            <div class="word">
                <pre><b>Сессия:</b> <?php echo $word['session_name']?></pre>
            </div>
        <?php endif;?>

        <?php if ($word['description']):?>
            <div class="word">
                <pre><b>Доп. инфо:</b> <?php echo $word['description']?></pre>
            </div>
        <?php endif;?>

        <table class="table">
        <?php foreach ($exercises as $e):?>
            <tr>
                <td><?php echo $e['done'] ? 'OK' : 'F'?></td>
                <td><?php echo $e['direction']?></td>
                <td><?php echo (new DateTime($e['created_at']))->format('d.m.Y H:i')?></td>
            </tr>
        <?php endforeach;?>
        </table>
    </form>

    <script type="text/javascript">
        $('.word-hide').click(function() {
            $(this).removeClass('word-hide');
            $('.form').show();
        });
    </script>
<?php else:?>
    <p><img src="/img/eralash_black.png" alt="Пока всё" style="max-width: 100%" /> </p>
<?php endif;?>
