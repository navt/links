<?php
    $this->load->view('common/head-0');
?>
<div class="container">
    <div class="header"></div> 
    <?php
        $attributes = ["method" => "POST"];
        echo form_open('links/addLinks', $attributes);
    ?>    
        <textarea name="links" cols="86" rows="10" placeholder="Введите сюда новые ссылки"></textarea>
        <br><br>
        <input type="submit" name="submit" value="Ввод">
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo site_url('links/')?>">&larr; Обратно</a>
    </form>
</div>
</body>
</html>