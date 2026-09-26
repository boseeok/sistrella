{{-- One editable variant row. $i = row index (or "__I__" in the JS template), $row = values --}}
<tr data-variant-row>
    <td>
        @if(! empty($row['id']))<input type="hidden" name="variants[{{ $i }}][id]" value="{{ $row['id'] }}">@endif
        <input type="hidden" name="variants[{{ $i }}][_delete]" value="0" data-delete-flag>
        <input type="text" name="variants[{{ $i }}][color]" value="{{ $row['color'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. Blush pink" maxlength="40">
    </td>
    <td><input type="color" name="variants[{{ $i }}][color_code]" value="{{ $row['color_code'] ?? '#cccccc' }}" class="form-control form-control-sm form-control-color" title="Swatch colour"></td>
    <td><input type="text" name="variants[{{ $i }}][size]" value="{{ $row['size'] ?? '' }}" class="form-control form-control-sm" placeholder="e.g. 12 stems" maxlength="40"></td>
    <td><input type="number" step="0.01" min="0" name="variants[{{ $i }}][price]" value="{{ $row['price'] ?? '' }}" class="form-control form-control-sm" placeholder="—"></td>
    <td><input type="number" min="0" name="variants[{{ $i }}][stock]" value="{{ $row['stock'] ?? 0 }}" class="form-control form-control-sm"></td>
    <td><input type="text" name="variants[{{ $i }}][sku]" value="{{ $row['sku'] ?? '' }}" class="form-control form-control-sm" placeholder="auto" maxlength="60"></td>
    <td class="text-center">
        <input type="hidden" name="variants[{{ $i }}][is_active]" value="0">
        <input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" class="form-check-input" @checked(! empty($row['is_active']))>
    </td>
    <td><button type="button" class="btn btn-sm btn-light text-danger" data-remove-variant title="Remove variant"><i class="bi bi-trash"></i></button></td>
</tr>
