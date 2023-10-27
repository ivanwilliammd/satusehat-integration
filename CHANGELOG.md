# Changelog

v1.1 : 
- Standardize json() function to result encoded one with pretty print and no escape sequence
- Major functional fix of Encounter & Condition function class. Conversion to ATOM type datetime
- Added beta functionality of Observation
- Fixing inconsistency and function in Condition
- Updated timezone format

v1.0 :
- First beta version with PHP Class and consistency update of ICD 10-column migration
- Added faster batch import using csv seeder library

v0.15 :
- Last v0 series internally tested for creating OAuth 2
- Shipped basic method for GET by NIK function
- Shipped POST / PUT on FHIR object directly at Encounter, Condition, Organization, Location
