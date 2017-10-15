<style>
    input
    {
        width: 500px;
        background-color: #efefef;
        color: black;
        border: 2px solid #ddd;
        border-radius: 30px;
        font-size: 20px;
        padding: 10px;
        box-sizing: border-box;
    }
    select
    {
        width: 500px;
        background-color: #efefef;
        color: black;
        border: 2px solid #ddd;
        border-radius: 30px;
        font-size: 20px;
        padding: 10px; 
    }
    input[type=submit]
    {
        width: 300px;
        background-color: #36b03c;
        color: white;
        font-size: 20px;
        padding: 15px 10px;
        margin-top: 10px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        letter-spacing: 2px;
    }
</style>
</br>
</br>
 <form action = 'includes/pdf.php' method = 'POST'>
     <label>Lotnisko wylotu:</br>
        <select name="departure">
            <?php foreach($airports as $airport):?>
                <option value ="<?php echo $airport['code']?>"><?php echo $airport['name']?></option>;
            <?php endforeach;?>
        </select>
    </label>
    </br>
    <label>Lotnisko przylotu:</br>
        <select name="arrival">
            <?php foreach($airports as $airport):?>
                <option value ="<?php echo $airport['code']?>"><?php echo $airport['name']?></option>;
            <?php endforeach;?>
        </select>
    </label>
    </br>
    </br>
    <input type = 'datetime-local' name = 'timeOfStart' placeholder="Czas startu w formacie YYYY-MM-DD"></input>
    </br>
    <input type = 'number' min ='0' step="1" name = 'timeOfFlight' placeholder="Długość lotu w sekundach"></input>
    </br>
    <input type = 'number' min ='0' step="0.01" name = 'price' placeholder="Cena lotu"></input>
    </br>
    <input type="submit" value="Generuj bilet PDF">
 </form>
