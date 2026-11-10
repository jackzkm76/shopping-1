<?php

final class DbResult
{
    public int $cursor = 0;

    public function __construct(public array $rows)
    {
    }
}

function db_query(PDO|string $connectionOrQuery, ?string $query = null): DbResult|bool
{
    if ($connectionOrQuery instanceof PDO) {
        $connection = $connectionOrQuery;

        if ($query === null) {
            throw new InvalidArgumentException('A SQL query is required.');
        }
    } else {
        global $con;

        if ($query !== null) {
            throw new InvalidArgumentException('Pass either a query or a connection and query.');
        }

        $connection = $con;
        $query = $connectionOrQuery;
    }

    if (!$connection instanceof PDO) {
        throw new RuntimeException('The PostgreSQL connection is not initialized.');
    }

    try {
        $statement = $connection->query($query);

        if ($statement->columnCount() === 0) {
            return true;
        }

        $rows = $statement->fetchAll(PDO::FETCH_BOTH);
        $legacyColumnNames = [
            'billingaddress' => 'billingAddress',
            'billingcity' => 'billingCity',
            'billingpincode' => 'billingPincode',
            'billingstate' => 'billingState',
            'categorydescription' => 'categoryDescription',
            'categoryname' => 'categoryName',
            'creationdate' => 'creationDate',
            'logintime' => 'loginTime',
            'orderdate' => 'orderDate',
            'orderid' => 'orderId',
            'orderstatus' => 'orderStatus',
            'paymentmethod' => 'paymentMethod',
            'postingdate' => 'postingDate',
            'productavailability' => 'productAvailability',
            'productcompany' => 'productCompany',
            'productdescription' => 'productDescription',
            'productid' => 'productId',
            'productimage1' => 'productImage1',
            'productimage2' => 'productImage2',
            'productimage3' => 'productImage3',
            'productname' => 'productName',
            'productprice' => 'productPrice',
            'productpricebeforediscount' => 'productPriceBeforeDiscount',
            'regdate' => 'regDate',
            'reviewdate' => 'reviewDate',
            'shippingaddress' => 'shippingAddress',
            'shippingcharge' => 'shippingCharge',
            'shippingcity' => 'shippingCity',
            'shippingpincode' => 'shippingPincode',
            'shippingstate' => 'shippingState',
            'updationdate' => 'updationDate',
            'useremail' => 'userEmail',
            'userid' => 'userId',
        ];

        foreach ($rows as &$row) {
            foreach ($row as $column => $value) {
                if (!is_string($column)) {
                    continue;
                }

                $normalizedColumn = strtolower($column);
                if ($normalizedColumn === 'subcategory') {
                    $legacyColumn = stripos($query, 'from products') !== false
                        ? 'subCategory'
                        : 'subcategory';
                } else {
                    $legacyColumn = $legacyColumnNames[$normalizedColumn] ?? null;
                }

                if ($legacyColumn !== null) {
                    $row[$legacyColumn] = $value;
                }
            }
        }
        unset($row);

        return new DbResult($rows);
    } catch (PDOException $exception) {
        error_log('PostgreSQL query failed: ' . $exception->getMessage());
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
        exit('A database query failed. Check the Vercel runtime logs and Supabase schema.');
    }
}

function db_num_rows(DbResult $result): int
{
    return count($result->rows);
}

function db_fetch_array(DbResult $result): array|false
{
    if (!isset($result->rows[$result->cursor])) {
        return false;
    }

    return $result->rows[$result->cursor++];
}

function db_escape(PDO $connection, string $value): string
{
    $quotedValue = $connection->quote($value);

    if ($quotedValue === false) {
        throw new RuntimeException('PostgreSQL could not quote a query value.');
    }

    return substr($quotedValue, 1, -1);
}
