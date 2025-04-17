<?php
$availableFacilities = [
    ['icon' => '🛏️', 'name' => 'Double bed'],
    ['icon' => '📶', 'name' => 'Wi-Fi'],
    ['icon' => '🚿', 'name' => 'Private Bathroom'],
    ['icon' => '📺', 'name' => 'Smart TV'],
    ['icon' => '☕', 'name' => 'Coffee Maker'],
    ['icon' => '🅿️', 'name' => 'Free Parking'],
    ['icon' => '❄️', 'name' => 'Air Conditioning'],
    ['icon' => '🐶', 'name' => 'Pet Friendly'],
];

// $selectedFacilities este un array cu valorile deja bifate, dacă e setat
$selectedFacilities = $selectedFacilities ?? [];
?>

<div class="mb-6">
  <p class="font-medium mb-2">Select Facilities:</p>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
    <?php foreach ($availableFacilities as $facility): 
      $value = $facility['icon'] . '|' . $facility['name'];
      $checked = in_array($value, $selectedFacilities) ? 'checked' : '';
    ?>
      <label class="flex items-center gap-2">
        <input type="checkbox" name="facilities[]" value="<?= $value ?>" <?= $checked ?>>
        <span><?= $facility['icon'] ?> <?= $facility['name'] ?></span>
      </label>
    <?php endforeach; ?>
  </div>
</div>
