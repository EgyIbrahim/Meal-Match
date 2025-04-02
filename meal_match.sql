-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 02, 2025 at 07:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meal_match`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `title`, `content`, `created_at`, `image_url`, `likes_count`, `comments_count`, `updated_at`) VALUES
(1, 2, 'Homemade Pizza Night!', '...', '2025-03-14 18:30:00', 'https://www.allrecipes.com/thmb/9UTj7kZBJDqory0cdEv_bw6Ef_0=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/48727-Mikes-homemade-pizza-DDMFS-beauty-2x1-BG-2976-d5926c9253d3486bbb8a985172604291.jpg', 13, 3, '2025-03-14 23:24:46'),
(2, 3, 'Avocado Toast Breakfast', '...', '2025-03-14 12:15:00', 'https://cdn77-s3.lazycatkitchen.com/wp-content/uploads/2017/03/vegan-avocado-toast-1000x1500.jpg', 23, 5, '2025-03-14 23:25:17'),
(3, 4, 'Weekend BBQ Party', '...', '2025-03-13 20:00:00', 'https://explore.liquorandwineoutlets.com/wp-content/uploads/2019/04/bbq5.jpeg', 45, 8, '2025-03-14 23:25:50'),
(4, 5, 'Matcha Latte Art', '...', '2025-03-13 09:45:00', 'https://i.etsystatic.com/19484688/r/il/adbd17/3636814361/il_fullxfull.3636814361_gkv9.jpg', 31, 6, '2025-03-14 23:26:20'),
(5, 6, 'Sushi Making Challenge', '...', '2025-03-12 19:20:00', 'https://yorkshirefoodguide.co.uk/wp-content/uploads/2024/06/Sushi-in-Leeds-The-Ivy-Asia-sushi.jpeg', 28, 4, '2025-03-14 23:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`) VALUES
(243, 'All-Purpose Flour'),
(244, 'Almond Milk'),
(245, 'Anchovies'),
(246, 'Apple Cider Vinegar'),
(247, 'Arborio Rice'),
(248, 'Artichoke Hearts'),
(249, 'Arugula'),
(250, 'Asparagus'),
(251, 'Avocado'),
(252, 'Bacon'),
(253, 'Baking Powder'),
(254, 'Baking Soda'),
(255, 'Balsamic Vinegar'),
(256, 'Banana'),
(257, 'Basil'),
(258, 'Bay Leaf'),
(259, 'Beef Broth'),
(260, 'Bell Pepper'),
(261, 'Black Beans'),
(262, 'Black Olives'),
(263, 'Blue Cheese'),
(264, 'Bok Choy'),
(265, 'Bread Crumbs'),
(266, 'Brie Cheese'),
(267, 'Broccoli'),
(268, 'Brown Sugar'),
(269, 'Brussels Sprouts'),
(270, 'Buckwheat'),
(271, 'Bulgar Wheat'),
(15, 'Butter'),
(272, 'Buttermilk'),
(273, 'Cabbage'),
(274, 'Capers'),
(275, 'Cardamom'),
(276, 'Carrot'),
(277, 'Cashews'),
(278, 'Catfish'),
(279, 'Cauliflower'),
(280, 'Celery'),
(281, 'Chia Seeds'),
(6, 'Chicken'),
(282, 'Chicken Broth'),
(283, 'Chickpeas'),
(284, 'Chili Flakes'),
(285, 'Chili Powder'),
(286, 'Cilantro'),
(287, 'Cinnamon'),
(288, 'Clams'),
(14, 'Cocoa Powder'),
(289, 'Coconut Oil'),
(290, 'Cod'),
(291, 'Coffee'),
(292, 'Coriander'),
(293, 'Corn'),
(294, 'Corn Starch'),
(295, 'Corn Tortillas'),
(296, 'Cornmeal'),
(297, 'Couscous'),
(298, 'Crab'),
(299, 'Cranberries'),
(300, 'Cream Cheese'),
(301, 'Cucumber'),
(302, 'Cumin'),
(303, 'Curry Leaves'),
(304, 'Dates'),
(305, 'Dill'),
(306, 'Duck'),
(307, 'Edamame'),
(308, 'Egg Noodles'),
(309, 'Eggplant'),
(3, 'Eggs'),
(310, 'Farfalle Pasta'),
(311, 'Fennel'),
(312, 'Feta Cheese'),
(313, 'Fish Sauce'),
(314, 'Flank Steak'),
(315, 'Flax Seeds'),
(11, 'Flour'),
(316, 'French Bread'),
(317, 'Garlic Powder'),
(318, 'Gelatin'),
(319, 'Ghee'),
(320, 'Ginger'),
(321, 'Goat Cheese'),
(322, 'Gouda Cheese'),
(323, 'Graham Crackers'),
(324, 'Granola'),
(325, 'Grapefruit'),
(326, 'Grapes'),
(327, 'Green Beans'),
(328, 'Green Lentils'),
(329, 'Green Olives'),
(330, 'Ground Beef'),
(331, 'Gruyere Cheese'),
(332, 'Haddock'),
(333, 'Halibut'),
(334, 'Ham'),
(335, 'Hazelnuts'),
(336, 'Honey'),
(337, 'Horseradish'),
(338, 'Hummus'),
(339, 'Iceberg Lettuce'),
(340, 'Jalapeno'),
(341, 'Jasmine Rice'),
(342, 'Kale'),
(343, 'Ketchup'),
(344, 'Kidney Beans'),
(345, 'Kimchi'),
(346, 'Lamb'),
(347, 'Lasagna Noodles'),
(348, 'Leek'),
(349, 'Lemon'),
(350, 'Lemon Zest'),
(351, 'Lentils'),
(352, 'Lime'),
(353, 'Linguine Pasta'),
(354, 'Lobster'),
(355, 'Macaroni'),
(356, 'Mango'),
(357, 'Maple Syrup'),
(358, 'Marjoram'),
(359, 'Mascarpone'),
(360, 'Mayonnaise'),
(12, 'Milk'),
(361, 'Mint'),
(362, 'Molasses'),
(363, 'Mozzarella'),
(364, 'Mushrooms'),
(365, 'Mussels'),
(366, 'Mustard'),
(367, 'Mustard Greens'),
(368, 'Nutmeg'),
(369, 'Oatmeal'),
(370, 'Octopus'),
(9, 'Oil'),
(371, 'Okra'),
(372, 'Olive Oil'),
(373, 'Onion Powder'),
(374, 'Orange'),
(375, 'Oregano'),
(376, 'Oyster Sauce'),
(377, 'Oysters'),
(2, 'Pancetta'),
(378, 'Paprika'),
(4, 'Parmesan'),
(379, 'Parsley'),
(380, 'Parsnip'),
(381, 'Peaches'),
(382, 'Peanut Butter'),
(383, 'Peanut Oil'),
(384, 'Peanuts'),
(385, 'Pearl Barley'),
(386, 'Pears'),
(387, 'Peas'),
(388, 'Pecans'),
(389, 'Penne Pasta'),
(5, 'Pepper'),
(390, 'Pine Nuts'),
(391, 'Pineapple'),
(392, 'Pistachios'),
(393, 'Plums'),
(394, 'Polenta'),
(395, 'Pomegranate'),
(396, 'Pork Chops'),
(397, 'Potato Starch'),
(398, 'Potatoes'),
(399, 'Prosciutto'),
(400, 'Pumpkin'),
(401, 'Pumpkin Seeds'),
(402, 'Quinoa'),
(403, 'Radicchio'),
(404, 'Radishes'),
(405, 'Raisins'),
(406, 'Raspberries'),
(407, 'Red Lentils'),
(408, 'Red Onion'),
(409, 'Red Pepper Flakes'),
(410, 'Red Wine'),
(411, 'Red Wine Vinegar'),
(412, 'Rhubarb'),
(413, 'Ribeye Steak'),
(8, 'Rice'),
(414, 'Ricotta Cheese'),
(415, 'Rosemary'),
(416, 'Saffron'),
(417, 'Sage'),
(418, 'Salmon'),
(419, 'Salsa'),
(420, 'Sardines'),
(421, 'Sausage'),
(422, 'Scallions'),
(423, 'Sea Salt'),
(424, 'Semolina'),
(425, 'Sesame Oil'),
(426, 'Sesame Seeds'),
(427, 'Shallots'),
(428, 'Sherry Vinegar'),
(429, 'Shrimp'),
(430, 'Sour Cream'),
(431, 'Soy Milk'),
(10, 'Soy Sauce'),
(1, 'Spaghetti'),
(432, 'Spinach'),
(433, 'Squash'),
(434, 'Sriracha'),
(435, 'Star Anise'),
(436, 'Strawberries'),
(13, 'Sugar'),
(437, 'Sunflower Oil'),
(438, 'Sunflower Seeds'),
(439, 'Sweet Potato'),
(440, 'Swiss Chard'),
(441, 'Tabasco Sauce'),
(442, 'Tahini'),
(443, 'Tarragon'),
(444, 'Thyme'),
(445, 'Tilapia'),
(446, 'Tofu'),
(447, 'Tomato Paste'),
(448, 'Tomato Sauce'),
(449, 'Tomatoes'),
(450, 'Trout'),
(451, 'Tuna'),
(452, 'Turmeric'),
(453, 'Turnip'),
(454, 'Vanilla Bean'),
(16, 'Vanilla Extract'),
(7, 'Vegetables'),
(455, 'Walnuts'),
(456, 'Water Chestnuts'),
(457, 'Watercress'),
(458, 'Watermelon'),
(459, 'Wheat Germ'),
(460, 'Whey Protein'),
(461, 'White Wine'),
(462, 'Worcestershire Sauce'),
(463, 'Yam'),
(464, 'Yeast'),
(465, 'Yellow Onion'),
(466, 'Yogurt'),
(467, 'Zucchini');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('image','video') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `steps` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `title`, `steps`, `image`, `created_at`, `category`, `area`) VALUES
(1, 0, 'Classic Spaghetti Carbonara', '1. Boil spaghetti; 2. Fry pancetta; 3. Mix eggs with Parmesan; 4. Combine with pasta; 5. Season and serve.', 'https://static01.nyt.com/images/2021/02/14/dining/carbonara-horizontal/carbonara-horizontal-threeByTwoMediumAt2X-v2.jpg', '2025-03-31 09:32:58', 'Italian', 'Europe'),
(2, 0, 'Chicken Stir-Fry', '1. Slice chicken and vegetables; 2. Heat oil in a pan; 3. Stir-fry chicken until golden; 4. Add vegetables and sauce; 5. Serve over rice.', 'https://i2.wp.com/www.downshiftology.com/wp-content/uploads/2021/05/Chicken-Stir-Fry-main.jpg', '2025-03-31 09:32:58', 'Asian', 'Asia'),
(3, 0, 'Decadent Chocolate Cake', '1. Mix dry ingredients; 2. Add eggs, milk, and oil; 3. Stir until smooth; 4. Bake in preheated oven; 5. Frost and enjoy.', 'https://chelsweets.com/wp-content/uploads/2021/08/death-by-chocolate-cake-cut-open-vert-scaled-735x1102.jpg', '2025-03-31 09:32:58', 'Dessert', 'Global'),
(4, 0, 'Margherita Pizza', '1. Roll dough; 2. Spread tomato sauce; 3. Add mozzarella and basil; 4. Bake at 220°C for 12 minutes.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Pizza_Margherita_stu_spivack.jpg/1200px-Pizza_Margherita_stu_spivack.jpg', '2025-03-31 17:07:49', 'Italian', 'Europe'),
(5, 0, 'Vegetable Lasagna', '1. Layer lasagna sheets; 2. Add spinach and ricotta; 3. Top with tomato sauce; 4. Bake for 45 minutes.', 'https://www.inspiredtaste.net/wp-content/uploads/2016/10/Easy-Vegetable-Lasagna-Recipe-1200.jpg', '2025-03-31 17:07:49', 'Italian', 'Europe'),
(6, 0, 'Chicken Tikka Masala', '1. Marinate chicken in yogurt and spices; 2. Grill chicken; 3. Simmer in creamy tomato sauce.', 'https://realfood.tesco.com/media/images/1400x919-Chicken-tikka-masala-43fcdbd8-eb86-4b55-951d-adda29067afa-0-1400x919.jpg', '2025-03-31 17:07:49', 'Indian', 'Asia'),
(7, 0, 'Greek Salad', '1. Chop cucumbers and tomatoes; 2. Add olives and feta; 3. Dress with olive oil.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmBKcXSTKfv5GY_Urwk7jPuDWiilZeB4AWgw&s', '2025-03-31 17:07:49', 'Salad', 'Mediterranean'),
(8, 0, 'Beef Burger', '1. Grill beef patty; 2. Toast bun; 3. Assemble with lettuce and tomato.', 'https://www.certifiedirishangus.ie/wp-content/uploads/2019/11/TheUltimateBurgerwBacon_RecipePic.jpg', '2025-03-31 17:07:49', 'Fast Food', 'North America'),
(9, 0, 'Sushi Rolls', '1. Prepare sushi rice; 2. Layer fish and veggies; 3. Roll with nori; 4. Slice.', 'https://www.justonecookbook.com/wp-content/uploads/2020/06/Dragon-Roll-0286-I-500x375.jpg', '2025-03-31 17:07:49', 'Japanese', 'Asia'),
(10, 0, 'Guacamole', '1. Mash avocados; 2. Mix with lime, onion, and cilantro; 3. Serve with chips.', 'https://www.giallozafferano.com/images/255-25549/Guacamole_650x433_wm.jpg', '2025-03-31 17:07:49', 'Appetizer', 'Mexico'),
(11, 0, 'Pancakes', '1. Mix flour and milk; 2. Cook batter on griddle; 3. Serve with syrup.', 'https://www.allrecipes.com/thmb/FE0PiuuR0Uh06uVh1c2AsKjRGbc=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/21014-Good-old-Fashioned-Pancakes-mfs_002-0e249c95678f446291ebc9408ae64c05.jpg', '2025-03-31 17:07:49', 'Breakfast', 'Global'),
(12, 0, 'Pho Soup', '1. Simmer beef broth; 2. Add rice noodles and herbs; 3. Top with beef slices.', 'https://www.munchkintime.com/wp-content/uploads/2023/04/Beef-Pho-Soup-15.jpg', '2025-03-31 17:07:49', 'Soup', 'Vietnam'),
(13, 0, 'Ratatouille', '1. Sauté eggplant and zucchini; 2. Layer with tomato sauce; 3. Bake.', 'https://cdn.apartmenttherapy.info/image/upload/f_jpg,q_auto:eco,c_fill,g_auto,w_1500,ar_1:1/k%2FPhoto%2FRecipes%2F2024-07-ratatouille%2FRatatouille-', '2025-03-31 17:07:49', 'Vegetarian', 'France'),
(14, 0, 'Cheesy Omelette', '1. Whisk eggs and milk; 2. Cook in butter; 3. Add Parmesan; 4. Fold and serve.', 'https://fyf20quid.co.uk/wp-content/uploads/cooked/images/recipes/recipe_12910.jpg', '2025-04-01 09:01:17', 'Breakfast', 'Global'),
(15, 0, 'Garlic Spaghetti', '1. Cook spaghetti; 2. Sauté garlic in oil; 3. Toss pasta with Parmesan.', 'https://mojo.generalmills.com/api/public/content/yevc5OLkpEucjR93lMFxvQ_gmi_hi_res_jpeg.jpeg?v=521561d2&t=466b54bb264e48b199fc8e83ef1136b4', '2025-04-01 09:01:17', 'Italian', 'Europe'),
(16, 0, 'Fried Rice', '1. Cook rice; 2. Sauté veggies in oil; 3. Add rice and soy sauce; 4. Stir-fry.', 'https://images.getrecipekit.com/20220904015448-veg-20fried-20rice.png?aspect_ratio=16:9&quality=90&', '2025-04-01 09:01:17', 'Asian', 'Asia'),
(17, 0, 'Simple Pancakes', '1. Mix flour, milk, and eggs; 2. Cook in butter; 3. Serve with syrup.', 'https://www.marthastewart.com/thmb/Vgb9cQSlegZz5fcoSbkkqyHPmHY=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/338185-basic-pancakes-09-00b18f8418fd4e52bb2050173d083d04.jpg', '2025-04-01 09:01:17', 'Breakfast', 'Global'),
(18, 0, 'Chocolate Pudding', '1. Mix cocoa, sugar, and milk; 2. Cook until thick; 3. Chill and serve.', 'https://thebigmansworld.com/wp-content/uploads/2024/09/chocolate-pudding-cake-recipe.jpg', '2025-04-01 09:01:17', 'Dessert', 'Global'),
(19, 0, 'Sautéed Vegetables', '1. Heat oil; 2. Stir-fry veggies; 3. Season with pepper.', 'https://www.budgetbytes.com/wp-content/uploads/2021/02/Simple-Saute%CC%81ed-Vegetables-side.jpg', '2025-04-01 09:01:17', 'Side Dish', 'Global'),
(20, 0, 'Pepper Chicken', '1. Sear chicken; 2. Season with pepper; 3. Bake until tender.', 'https://i.ytimg.com/vi/Zs9EBc3zPPU/maxresdefault.jpg', '2025-04-01 09:01:17', 'Main Course', 'Global'),
(21, 0, 'Buttered Rice', '1. Cook rice; 2. Toss with butter and pepper.', 'https://themenumaid.com/wp-content/uploads/2021/08/IMG_E1797-blog-photo.jpg', '2025-04-01 09:01:17', 'Side Dish', 'Global'),
(22, 0, 'Vanilla Custard', '1. Heat milk and sugar; 2. Add vanilla; 3. Thicken with eggs.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQY1pjJZAG1zvqVlKhEevoXWSFq_RVjFYRhpQ&s', '2025-04-01 09:01:17', 'Dessert', 'Global'),
(23, 0, 'Spicy Stir-Fry', '1. Sauté chicken and veggies; 2. Add soy sauce and pepper.', 'https://www.chilipeppermadness.com/wp-content/uploads/2021/12/Hunan-Chicken-SQ.jpg', '2025-04-01 09:01:17', 'Asian', 'Asia'),
(24, 0, 'Cheese Toast', '1. Butter bread; 2. Add Parmesan; 3. Grill until melted.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS44ZJ7s7sW7_l9fYlj6oKSJ8LfuTQxWNvWKA&s', '2025-04-01 09:01:17', 'Snack', 'Global'),
(25, 0, 'Egyptian Koshari', '1. Cook lentils and rice; 2. Boil pasta; 3. Fry onions; 4. Layer with tomato sauce.', 'https://example.com/koshari.jpg', '2025-04-02 05:29:49', 'Egyptian', 'Egypt'),
(26, 0, 'Vegan Lentil Curry', '1. Sauté onions; 2. Add lentils and spices; 3. Simmer with almond milk.', 'https://example.com/lentil-curry.jpg', '2025-04-02 05:29:49', 'Vegan', 'Global'),
(27, 0, 'Chickpea Salad', '1. Mix chickpeas, veggies; 2. Dress with olive oil and lemon.', 'https://example.com/chickpea-salad.jpg', '2025-04-02 05:29:49', 'Vegan', 'Mediterranean'),
(28, 0, 'Stuffed Grape Leaves', '1. Blanch grape leaves; 2. Fill with rice and herbs; 3. Steam.', 'https://example.com/grape-leaves.jpg', '2025-04-02 05:29:49', 'Vegan', 'Middle Eastern'),
(29, 0, 'Tofu Stir-Fry', '1. Press tofu; 2. Sauté with veggies; 3. Add soy sauce.', 'https://example.com/tofu-stirfry.jpg', '2025-04-02 05:29:49', 'Vegan', 'Asian'),
(30, 0, 'Falafel', '1. Blend chickpeas and herbs; 2. Form patties; 3. Fry.', 'https://example.com/falafel.jpg', '2025-04-02 05:29:49', 'Vegan', 'Egyptian'),
(31, 0, 'Vegan Chocolate Mousse', '1. Blend avocado and cocoa; 2. Sweeten with maple syrup.', 'https://example.com/vegan-mousse.jpg', '2025-04-02 05:29:49', 'Vegan', 'Global'),
(32, 0, 'Hummus', '1. Blend chickpeas; 2. Add tahini and lemon; 3. Serve.', 'https://example.com/hummus.jpg', '2025-04-02 05:29:49', 'Vegan', 'Middle Eastern'),
(33, 0, 'Okra Stew', '1. Sauté okra; 2. Add tomatoes; 3. Simmer with spices.', 'https://example.com/okra-stew.jpg', '2025-04-02 05:29:49', 'Vegan', 'Egyptian'),
(34, 0, 'Ful Medames', '1. Simmer fava beans; 2. Mash with garlic and lemon.', 'https://example.com/ful-medames.jpg', '2025-04-02 05:29:49', 'Vegan', 'Egyptian');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id`, `recipe_id`, `ingredient_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 2, 6),
(7, 2, 7),
(8, 2, 8),
(9, 2, 9),
(10, 2, 10),
(11, 3, 11),
(12, 3, 3),
(13, 3, 12),
(14, 3, 13),
(15, 3, 14),
(16, 3, 15),
(17, 3, 16),
(18, 4, 3),
(19, 4, 12),
(20, 4, 4),
(21, 4, 15),
(22, 5, 1),
(23, 5, 9),
(24, 5, 4),
(25, 5, 5),
(26, 6, 6),
(27, 6, 466),
(28, 6, 452),
(29, 6, 320),
(30, 6, 449),
(31, 6, 372),
(32, 7, 301),
(33, 7, 449),
(34, 7, 262),
(35, 7, 312),
(36, 7, 372),
(37, 8, 330),
(38, 8, 339),
(39, 8, 449),
(40, 8, 465),
(41, 8, 9),
(42, 9, 341),
(43, 9, 418),
(44, 9, 301),
(45, 9, 252),
(46, 9, 313),
(47, 10, 251),
(48, 10, 352),
(49, 10, 465),
(50, 10, 286),
(51, 10, 5),
(52, 11, 243),
(53, 11, 12),
(54, 11, 3),
(55, 11, 13),
(56, 11, 15),
(57, 12, 259),
(58, 12, 414),
(59, 12, 443),
(60, 12, 257),
(61, 12, 320),
(62, 13, 309),
(63, 13, 467),
(64, 13, 449),
(65, 13, 260),
(66, 13, 372),
(67, 14, 3),
(68, 14, 12),
(69, 14, 15),
(70, 14, 4),
(71, 14, 5),
(72, 15, 1),
(73, 15, 9),
(74, 15, 4),
(75, 15, 5),
(76, 16, 8),
(77, 16, 7),
(78, 16, 9),
(79, 16, 10),
(80, 16, 3),
(81, 17, 11),
(82, 17, 12),
(83, 17, 3),
(84, 17, 15),
(85, 17, 13),
(86, 18, 14),
(87, 18, 13),
(88, 18, 12),
(89, 18, 294),
(90, 18, 16),
(91, 19, 9),
(92, 19, 7),
(93, 19, 5),
(94, 20, 6),
(95, 20, 5),
(96, 20, 9),
(97, 21, 8),
(98, 21, 15),
(99, 21, 5),
(100, 22, 12),
(101, 22, 13),
(102, 22, 16),
(103, 22, 3),
(104, 23, 6),
(105, 23, 7),
(106, 23, 10),
(107, 23, 5),
(108, 23, 9),
(109, 24, 15),
(110, 24, 4),
(111, 24, 316),
(112, 25, 8),
(113, 25, 351),
(114, 25, 1),
(115, 25, 448),
(116, 25, 465),
(117, 25, 317),
(118, 25, 9),
(119, 26, 351),
(120, 26, 465),
(121, 26, 317),
(122, 26, 244),
(123, 26, 449),
(124, 26, 452),
(125, 26, 302),
(126, 27, 283),
(127, 27, 301),
(128, 27, 449),
(129, 27, 312),
(130, 27, 372),
(131, 27, 349),
(132, 28, 8),
(133, 28, 465),
(134, 28, 379),
(135, 28, 372),
(136, 28, 349),
(137, 29, 446),
(138, 29, 7),
(139, 29, 10),
(140, 29, 9),
(141, 30, 283),
(142, 30, 465),
(143, 30, 379),
(144, 30, 302),
(145, 30, 9),
(146, 31, 251),
(147, 31, 14),
(148, 31, 357),
(149, 32, 283),
(150, 32, 442),
(151, 32, 349),
(152, 32, 372),
(153, 33, 371),
(154, 33, 449),
(155, 33, 465),
(156, 33, 302),
(157, 34, 261),
(158, 34, 317),
(159, 34, 349);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `profile_picture`, `bio`, `created_at`) VALUES
(0, 'default', 'default@example.com', 'dummyhash', NULL, NULL, '2025-03-31 09:25:50'),
(2, 'john_doe', 'john.doe@example.com', 'hashed_password_1', 'profile1.jpg', 'Loves coding and gaming.', '2025-03-01 10:15:00'),
(3, 'jane_smith', 'jane.smith@example.com', 'hashed_password_2', 'profile2.jpg', 'A passionate developer and tech enthusiast.', '2025-02-15 09:45:00'),
(4, 'mark_taylor', 'mark.taylor@example.com', 'hashed_password_3', 'profile3.jpg', 'Interested in AI and data science.', '2025-01-10 14:30:00'),
(5, 'emily_james', 'emily.james@example.com', 'hashed_password_4', 'profile4.jpg', 'Web developer and UX/UI designer.', '2025-03-05 16:00:00'),
(6, 'alex_brown', 'alex.brown@example.com', 'hashed_password_5', 'profile5.jpg', 'Cybersecurity specialist and ethical hacker.', '2025-02-20 12:00:00'),
(7, 'gg', 'gg@gg.com', '$2y$10$vi9OWhzAokOLUqqR0FFf5eegDhaeS5/1j/Ky29WX821kJGI6v26T.', NULL, 'I like food', '2025-03-14 23:11:11'),
(8, 'ehtesham', 'siddiqui123@gmail.com', '$2y$10$U9i5mOejRS9s3cylmdwqKOvdRJioE1t8yLClKm7mZ6oS0uBIe.tO2', NULL, NULL, '2025-03-17 15:34:32'),
(9, 'hello', 'hello@gmail.com', '$2y$10$KFjxe2xLqNZDlK0.b4wJI.56dEnBWj0AkrRYxnvPy03GYoCHWVKSm', NULL, NULL, '2025-03-24 15:25:27'),
(10, 'hh', 'hhh@gmail.com', '$2y$10$UmE2Owpj3KRoEUSiWJ9LAOhfMDA7fTcDyImDOTrdD5K0Yi80I6GM.', NULL, NULL, '2025-03-27 11:32:09'),
(15, 'user1', 'user1@gmail.com', '$2y$10$hPzK9mKy5Y1TAIJbG33ZjObRvhIDGKUOe2JZTnQhKkDOvv1Z3QnO.', NULL, NULL, '2025-04-01 13:07:38'),
(16, 'john', 'user2@gmail.com', '$2y$10$Y6FULZaamag6/qhuA4SuGu9ndNTxvwWnsw2KYZNzEOBmV94Q9VhoK', NULL, '', '2025-04-01 13:15:20');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dark_mode` tinyint(1) DEFAULT 0,
  `notifications` tinyint(1) DEFAULT 1,
  `language` varchar(10) DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `dark_mode`, `notifications`, `language`) VALUES
(2, 7, 0, 1, 'en'),
(3, 8, 0, 1, 'en'),
(4, 9, 0, 1, 'en'),
(5, 10, 0, 1, 'en'),
(6, 15, 0, 1, 'en'),
(7, 16, 0, 1, 'en');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=468;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `media_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
