<?php

class RecordController
{
    private const PER_PAGE = 10;

    public function index(): array
    {
        return Record::allWithDeleted();
    }

    public function paginate(int $page = 1): array
    {
        return Record::paginate($page, self::PER_PAGE);
    }

    public function totalPages(): int
    {
        return (int)ceil(Record::count() / self::PER_PAGE);
    }

    public function show(int $id): ?Record
    {
        return Record::find($id);
    }

    public function insert(array $data): Record
    {
        $record = new Record();
        $record->name = $data['name'];
        $record->save();
        return $record;
    }

    public function update(int $id, array $data): ?Record
    {
        $record = Record::findWithDeleted($id);
        if (!$record) return null;

        $record->name = $data['name'] ?? $record->name;
        $record->save();
        return $record;
    }

    public function delete(int $id): bool
    {
        $record = Record::findWithDeleted($id);
        return $record ? $record->delete() : false;
    }

    public function restore(int $id): bool
    {
        $record = Record::findWithDeleted($id);
        return $record ? $record->restore() : false;
    }
}
