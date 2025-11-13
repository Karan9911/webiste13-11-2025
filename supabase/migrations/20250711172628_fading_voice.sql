-- SQL queries for database changes

-- 1. Remove price_per_session column from therapists table
ALTER TABLE therapists DROP COLUMN price_per_session;

-- 2. Add points field to services table
ALTER TABLE services ADD COLUMN points TEXT AFTER description;

-- 3. Add address field to bookings table
ALTER TABLE bookings ADD COLUMN address TEXT AFTER phone;

-- 4. Update existing services with sample points
UPDATE services SET points = 'Professional certified therapists|Premium quality products|Relaxing ambiance|Customized treatment' WHERE name = 'Swedish Massage';
UPDATE services SET points = 'Deep muscle tension relief|Sports injury recovery|Chronic pain management|Improved circulation' WHERE name = 'Deep Tissue Massage';
UPDATE services SET points = 'Heated volcanic stones|Deep muscle relaxation|Improved blood flow|Stress reduction' WHERE name = 'Hot Stone Therapy';
UPDATE services SET points = 'Essential oils therapy|Mood enhancement|Anxiety relief|Better sleep quality' WHERE name = 'Aromatherapy';
UPDATE services SET points = 'Pressure point massage|Energy balance restoration|Organ function improvement|Overall wellness boost' WHERE name = 'Reflexology';
UPDATE services SET points = 'Traditional stretching techniques|Improved flexibility|Energy flow enhancement|Joint mobility improvement' WHERE name = 'Thai Massage';