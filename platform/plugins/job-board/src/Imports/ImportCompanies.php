<?php

namespace Botble\JobBoard\Imports;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Contracts\OnSuccesses;
use Botble\JobBoard\Contracts\Typeable;
use Botble\JobBoard\Contracts\Validatable;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\Location\Models\City;
use Botble\Location\Models\Country;
use Botble\Location\Models\State;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Symfony\Component\Mime\MimeTypes;

class ImportCompanies implements ToModel, WithChunkReading, WithHeadingRow, WithMapping, WithValidation
{
    use Importable;
    use OnSuccesses;
    use SkipsErrors;
    use SkipsFailures;
    use Typeable;
    use Validatable;

    public function __construct(protected Request $request)
    {
    }

    public function model(array $row)
    {
        $company = new Company();
        $company->forceFill(Arr::except($row, ['accounts', 'slug']));
        $company->save();

        $company->accounts()->sync(Arr::get($row, 'accounts', []));

        $this->request->merge([
            'slug' => Arr::get($row, 'slug') ?: Str::slug($company->name),
            'is_slug_editable' => true,
        ]);

        event(new CreatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $this->request, $company));

        $this->onSuccess($company);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function map($row): array
    {
        $logo = explode(',', Arr::get($row, 'logo', ''));
        $coverImage = explode(',', Arr::get($row, 'cover_image', ''));

        $companies = [
            'name' => Arr::get($row, 'name'),
            'email' => Arr::get($row, 'email'),
            'slug' => Arr::get($row, 'slug'),
            'description' => Arr::get($row, 'description'),
            'content' => Arr::get($row, 'content'),
            'website' => Arr::get($row, 'website'),
            'logo' => implode($this->getImageURLs($logo)),
            'latitude' => Arr::get($row, 'latitude'),
            'longitude' => Arr::get($row, 'longitude'),
            'address' => Arr::get($row, 'address'),
            'postal_code' => Arr::get($row, 'postal_code'),
            'phone' => Arr::get($row, 'phone'),
            'tax_id' => (string) Arr::get($row, 'tax_id'),
            'year_founded' => Arr::get($row, 'year_founded'),
            'ceo' => Arr::get($row, 'ceo'),
            'number_of_offices' => Arr::get($row, 'number_of_offices'),
            'number_of_employees' => Arr::get($row, 'number_of_employees'),
            'annual_revenue' => Arr::get($row, 'annual_revenue'),
            'cover_image' => implode($this->getImageURLs($coverImage)),
            'facebook' => Arr::get($row, 'facebook'),
            'linkedin' => Arr::get($row, 'linkedin'),
            'twitter' => Arr::get($row, 'twitter'),
            'instagram' => Arr::get($row, 'instagram'),
            'is_featured' => $this->yesNoToBoolean(strtolower(Arr::get($row, 'is_featured', false))),
            'status' => Arr::get($row, 'status'),
        ];

        return $this->mapRelationships($row, $companies);
    }

    public function mapRelationships(mixed $row, array $companies): array
    {
        $companies['country_id'] = Arr::first($this->getIdsFromString(Arr::get($row, 'country'), new Country()));
        $companies['state_id'] = Arr::first($this->getIdsFromString(Arr::get($row, 'state'), new State()));
        $companies['city_id'] = Arr::first($this->getIdsFromString(Arr::get($row, 'city'), new City()));
        $companies['accounts'] = $this->getIdsFromString(Arr::get($row, 'account_manager'), new Account(), 'first_name');

        return $companies;
    }

    protected function getIdsFromString(?string $value, BaseModel $model, string $column = 'name'): ?array
    {
        if (! $value) {
            return null;
        }

        $items = $this->stringToArray($value);

        $ids = [];

        foreach ($items as $index => $item) {
            if (is_numeric($item)) {
                $column = 'id';
            }

            $ids[$index] = $model->where($column, $item)->value('id');
        }

        return array_filter($ids);
    }

    protected function getImageURLs(array $images): array
    {
        $images = array_values(array_filter($images));

        foreach ($images as $key => $image) {
            $images[$key] = str_replace(RvMedia::getUploadURL() . '/', '', trim($image));
            if (Str::contains($images[$key], ['http://', 'https://'])) {
                $images[$key] = $this->uploadImageFromURL($images[$key]);
            }
        }

        return $images;
    }

    protected function uploadImageFromURL(?string $url): ?string
    {
        if (empty($url)) {
            return $url;
        }

        $info = pathinfo($url);

        try {
            $contents = file_get_contents($url);
        } catch (Exception) {
            return $url;
        }

        if (empty($contents)) {
            return $url;
        }

        $path = '/tmp';

        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
        }

        $path = $path . '/' . $info['basename'];

        file_put_contents($path, $contents);

        $mimeType = Arr::first((new MimeTypes())->getMimeTypes(File::extension($url)));

        $fileUpload = new UploadedFile($path, $info['basename'], $mimeType, null, true);

        $result = RvMedia::handleUpload($fileUpload, 0, 'companies');

        File::delete($path);

        if (! $result['error']) {
            $url = $result['data']->url;
        }

        return $url;
    }
}
