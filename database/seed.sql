-- EcoCommerce Seed Data
-- Run after schema.sql
USE ecocommerce;

INSERT INTO products (name, category, description, price, image, hover_image, rating, review_count, stock) VALUES
(
    'EchoStore Herbal Gugo Shampoo 250ml',
    'EcoBeauty Basics',
    'Revitalize your hair naturally with our Gugo bark-based formula. Trusted by both men and women for its proven effectiveness against premature baldness, alopecia, and other hair concerns. Pair it with our conditioner and hair lotion for a complete recovery treatment.',
    210.00,
    'images/products/1.png',
    'images/products/2.png',
    4.5,
    89,
    100
),
(
    'EchoStore Virgin Coconut Oil 250ml',
    'EcoBeauty Basics',
    'Rejuvenate your well-being with our 100% Virgin Coconut Oil, harvested from the sun-kissed groves of the Philippines. Packed with essential nutrients, this natural elixir empowers your vitality, bolsters immunity, and aids in weight management. Embrace its electrolyte-rich goodness, promoting skin radiance, heart health, and digestive harmony.',
    290.00,
    'images/products/3.png',
    'images/products/4.png',
    5.0,
    24,
    100
),
(
    'Arka Naturals Activated Charcoal Soap | Scented 140g',
    'EcoBeauty Basics',
    'Elevate your skincare routine with this soap. This 140g bar is a blend of purifying activated charcoal and a refreshing mix of floral and herb notes. Vegan, cruelty-free, and free of SLS and parabens, it deeply cleanses, controls oil, and fights acne, leaving your skin flawless and pores tightened.',
    299.00,
    'images/products/5.png',
    'images/products/6.png',
    3.0,
    30,
    100
),
(
    'Arka Naturals Lavender Soap | Scented 140g',
    'EcoBeauty Basics',
    'Indulge in the calming essence of lavender with this soap. Formulated with anti-aging, anti-inflammatory, and antibacterial properties, this bar offers a soothing floral scent akin to lavender fields. Lavender\'s natural abilities calm and reduce inflammation, making it perfect for soothing skin irritations and insect bites.',
    299.00,
    'images/products/7.png',
    'images/products/8.png',
    4.5,
    91,
    100
),
(
    'Figtree Farms Tablea de Cacao Gourmet Chocolate 250g',
    'EcoGourmet Goods',
    'Indulge in the rich, chocolatey goodness of our 100% pure cacao tablea rolls! Made from locally grown and harvested cacao beans, each slice is a burst of authentic roasted cacao flavor. Perfect for champorado or hot chocolate, and sweetened with organic muscovado.',
    169.00,
    'images/products/9.png',
    'images/products/10.png',
    5.0,
    10,
    100
),
(
    'Kultura Pandan Tote Bag in Blue with Zipper',
    'EcoFashion Finds',
    'Elevate your style while championing local craftsmanship with our Pandan Tikog Tote Bag. Handwoven by Filipino artisans, this bag boasts a unique blend of tradition and modernity. Crafted from sturdy Pandan fibers, it features braided handles for added durability and a zip closure for security.',
    899.00,
    'images/products/11.png',
    'images/products/12.png',
    5.0,
    58,
    100
),
(
    'Kultura Tikog Handwoven Tote Bag with Red Stripes',
    'EcoFashion Finds',
    'Handwoven by skilled Filipino artisans, this bag is meticulously crafted from resilient Tikog fibers, offering both durability and style. Its braided handles ensure comfortable carrying, while the zip closure provides security for your belongings.',
    899.00,
    'images/products/13.png',
    'images/products/14.png',
    4.5,
    69,
    100
),
(
    'Lakbawayan Bamboo Notebook with Bamboo Pen',
    'EcoHome Essentials',
    'Jot down your adventures in style while making a positive impact on the environment with our Lakbawayan Bamboo Notebook. Crafted with a bamboo cover and 70 pages of recycled paper, this notebook combines sustainability with functionality.',
    139.00,
    'images/products/15.png',
    'images/products/16.png',
    3.5,
    33,
    100
),
(
    'Lakbawayan™ Kapecup (400mL) [Coffee Tumbler / Cup]',
    'EcoHome Essentials',
    'Meet the Lakbawayan™ Kapecup, coffee tumbler made especially for your coffee energizer, whether it for work or plain travel adventures. Made from sustainably sourced bamboo and best made stainless steel that can keep your morning a little bit fun and eco-friendly.',
    199.00,
    'images/products/17.png',
    'images/products/18.png',
    4.5,
    75,
    100
),
(
    'Figtree Farms Biodegradable Abaca Face Mask',
    'EcoFashion Finds',
    'Figtree Farms\' plantable, biodegradable, reusable abaca face masks are an eco-friendly take on the essential facial covering and help to solve the problem of disposable masks by highlighting how they can be made more sustainable.',
    149.00,
    'images/products/23.png',
    'images/products/24.png',
    4.0,
    92,
    100
);
