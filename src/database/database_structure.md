# Database Structure

The following example show what the database structure is, and what it stores.

## UserTypes

The UserTypes table stores the different user types. Namely these are:

- Admin
- Guest
- User

```sql
CREATE TABLE IF NOT EXISTS UserTypes (
    UserTypeId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    UserTypeName VARCHAR(16) NOT NULL UNIQUE
);
```

It has a one-to-many relationship with the User table.

## User

The User table stores the user credentials and details.

```sql
CREATE TABLE IF NOT EXISTS Users (
    UserId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Username VARCHAR(256) NOT NULL UNIQUE,
    HashedPassword VARCHAR(512) NOT NULL,
    UserTypeId INTEGER NOT NULL,
    FOREIGN KEY (UserTypeId) REFERENCES UserTypes(UserTypeId)
);
```

It has a many-to-one relationship with the UserTypes table.
It has a one-to-many relationship with the UserContactDetails table.

## UserContactDetails

The UserContactDetails table stores address and contact (phone number and email) for a user.

```sql
CREATE TABLE IF NOT EXISTS UserContactDetails (
    UserContactDetailsId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    UserId INTEGER NOT NULL,
    AddressLine1 VARCHAR(256) NOT NULL,
    AddressLine2 VARCHAR(256),
    TownCity VARCHAR(256) NOT NULL,
    County VARCHAR(256) NOT NULL,
    Postcode VARCHAR(8) NOT NULL,
    PhoneNumber VARCHAR(16),
    Email VARCHAR(512) NOT NULL,
    FOREIGN KEY (UserId) REFERENCES Users(UserId)
);
```

It has a one-to-many relationship with the Invoices table.
It has a many-to-one relationship with the Users table.

## Invoices

The Invoices table stores top level information about a user, such as the contact details associated with it and the time it was created.

```sql
CREATE TABLE IF NOT EXISTS Invoices (
    InvoiceId INTEGER PRIMARY KEY AUTO_INCREMENT,
    InvoiceValuePence INTEGER NOT NULL,
    DateTimeCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UserContactDetailsId INTEGER NOT NULL,
    FOREIGN KEY (UserContactDetailsId) REFERENCES UserContactDetails(UserContactDetailsId)
);
```

It has a many-to-one relationship with the UserContactDetails table.
It has a one-to-many relationship with the InvoiceDetails table.

## Products

The Products table stores information about a specific product.

```sql
CREATE TABLE IF NOT EXISTS Products (
    ProductId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    ProductName VARCHAR(256) NOT NULL UNIQUE,
    ProductDescription VARCHAR(1024) NOT NULL,
    ProductImageBase64 VARCHAR(1048576),
    ProductPricePence INTEGER NOT NULL
);
```

It has a many-to-one relationship with the InvoiceDetails table.

## InvoiceDetails

The InvoiceDetails table stores information about the items on an invoice.

```sql
CREATE TABLE IF NOT EXISTS InvoiceDetails (
    InvoiceDetailsId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    InvoiceId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (InvoiceId) REFERENCES Invoices(InvoiceId),
    FOREIGN KEY (ProductId) REFERENCES Products(ProductId)
);
```

It has a many-to-one relationship with the Invoices table.
It has a one-to-many relationship with the Products table.