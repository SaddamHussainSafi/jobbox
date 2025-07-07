<?php

namespace Botble\JobBoard\Imports;

use Botble\Base\Events\CreatedContentEvent;
use Botble\JobBoard\Contracts\OnSuccesses;
use Botble\JobBoard\Contracts\Typeable;
use Botble\JobBoard\Contracts\Validatable;
use Botble\JobBoard\Models\Account;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccountsImport implements
    ToModel,
    WithValidation,
    WithChunkReading,
    WithHeadingRow,
    WithMapping
{
    use Importable;
    use SkipsFailures;
    use SkipsErrors;
    use Validatable;
    use OnSuccesses;
    use Typeable;

    public function __construct(protected Request $request)
    {
    }

    public function model(array $row): Model
    {
        $account = new Account();

        $account->forceFill(Arr::except($row, ['password', 'country', 'state', 'city', 'date_of_birth']));
        $account->confirmed_at = Carbon::now();

        if (Arr::get($row, 'password')) {
            $password = Arr::get($row, 'password');
        } else {
            $password = Str::random(32);
        }

        $account->password = Hash::make($password);
        $account->save();

        $this->request->merge([
            'slug' => Arr::get($row, 'slug') ?: Str::slug($account->name),
            'is_slug_editable' => true,
        ]);

        event(new CreatedContentEvent(ACCOUNT_MODULE_SCREEN_NAME, $this->request, $account));

        $this->onSuccess($account);

        return $account;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function map($row): array
    {
        $account = array_merge($row, [
            'is_public_profile' => $this->yesNoToBoolean(Arr::get($row, 'is_public_profile')),
            'is_featured' => $this->yesNoToBoolean(Arr::get($row, 'is_featured')),
            'available_for_hiring' => $this->yesNoToBoolean(Arr::get($row, 'available_for_hiring')),
            'dob' => Arr::get($row, 'date_of_birth'),
            'views' => (int) Arr::get($row, 'views'),
        ]);

        return $this->mapRelationships($row, $account);
    }

    public function mapRelationships(mixed $row, array $account): array
    {
        $account['country_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'country'), new Country()));
        $account['state_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'state'), new State()));
        $account['city_id'] = Arr::first($this->stringToModelIds(Arr::get($row, 'city'), new City()));

        return $account;
    }
}
