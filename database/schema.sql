-- GhumauneyNepal database schema
-- Import this file through phpMyAdmin (Import tab) or mysql CLI.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS ghumauneynepal
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ghumauneynepal;

DROP TABLE IF EXISTS trip_plans;
DROP TABLE IF EXISTS wishlist;
DROP TABLE IF EXISTS destinations;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE destinations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  province VARCHAR(100),
  location VARCHAR(150),
  category VARCHAR(100),
  description TEXT,
  best_time VARCHAR(150),
  duration VARCHAR(100),
  budget VARCHAR(50),
  estimated_budget DECIMAL(10,2),
  image VARCHAR(255),
  highlights TEXT,
  latitude DECIMAL(10,7),
  longitude DECIMAL(10,7),
  accessibility VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  destination_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user_destination (user_id, destination_id),
  CONSTRAINT fk_wishlist_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_wishlist_destination
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trip_plans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  destination_id INT NULL,
  travelers INT NOT NULL,
  days INT NOT NULL,
  travel_type VARCHAR(100),
  budget DECIMAL(10,2),
  interests TEXT,
  travel_date DATE NULL,
  transportation VARCHAR(100),
  accommodation VARCHAR(100),
  special_requirements TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_trip_plans_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_trip_plans_destination
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO destinations (
  id,
  name,
  province,
  location,
  category,
  description,
  best_time,
  duration,
  budget,
  estimated_budget,
  image,
  highlights,
  latitude,
  longitude,
  accessibility
) VALUES
(1, 'Mount Everest / Everest Base Camp', 'Koshi Province', NULL, 'Mountain', 'The iconic Himalayan giant, with the classic trek to Everest Base Camp and dramatic mountain scenery.', 'March–May or September–November', '12–16 days', 'Premium', NULL, 'image/mount everest.jpg', '["Everest Base Camp trek", "Khumbu Valley", "High-altitude vistas"]', 27.9881, 86.925, NULL),
(2, 'Ilam', 'Koshi Province', NULL, 'Nature', 'A hill destination famous for lush tea gardens, hilltop views, and calm eastern landscapes.', 'September–November or March–May', '2–4 days', 'Budget', NULL, 'image/ilam.jpg', '["Tea gardens", "Scenic walks", "Cool climate"]', 26.9015, 87.9262, NULL),
(3, 'Kanyam', 'Koshi Province', NULL, 'Nature', 'A tranquil tea estate area with wide views, gentle hikes, and a relaxed eastern Nepal atmosphere.', 'September–November', '1–2 days', 'Budget', NULL, 'image/kanyam.jpg', '["Tea terraces", "Sunrise viewpoints", "Hillside tourism"]', 26.965, 87.9, NULL),
(4, 'Pathibhara', 'Koshi Province', NULL, 'Spiritual', 'A revered pilgrimage site high in the eastern Himalayas, known for its shrine and panoramic mountain views.', 'September–November', '2–3 days', 'Budget', NULL, 'image/pathibhara.jpg', '["Pilgrimage", "Holy shrine", "Panoramic views"]', 27.2924, 87.6229, NULL),
(5, 'Baraha Kshetra', 'Koshi Province', NULL, 'Spiritual', 'A sacred river confluence shrine on the Saptakoshi, visited for pilgrimage ceremonies and peaceful river views.', 'October–March', '1 day', 'Budget', NULL, 'image/baraha kshetra.jpg', '["River confluence", "Temple complex", "Pilgrimage"]', 26.7181, 87.1061, NULL),
(6, 'Halesi Tuwachung', 'Koshi Province', NULL, 'Spiritual', 'A sacred cave temple complex revered by Hindu and Buddhist pilgrims, set in forested hills.', 'September–November', '1–2 days', 'Budget', NULL, 'image/halesi tuwachung.jpg', '["Sacred cave", "Pilgrimage", "Forest setting"]', 26.1625, 86.8921, NULL),
(7, 'Makalu Base Camp', 'Koshi Province', NULL, 'Mountain', 'A remote high-altitude trek to the base of Makalu, offering dramatic Himalayan wilderness and alpine camping.', 'March–May or September–November', '18–22 days', 'Premium', NULL, 'image/makalu base camp.jpg', '["Remote trek", "Alpine scenery", "High mountain base camp"]', 27.8822, 87.0856, NULL),
(8, 'Janakpur', 'Madhesh Province', NULL, 'Culture', 'A sacred city tied to the Ramayana, famous for temple culture, festivals, and local craftsmanship.', 'October–March', '2–3 days', 'Budget', NULL, 'image/janakpur.jpg', '["Ramayana heritage", "Janaki Mandir", "Festivals"]', 26.7283, 85.9215, NULL),
(9, 'Janaki Mandir', 'Madhesh Province', NULL, 'Spiritual', 'A magnificent temple dedicated to Sita, noted for its white marble architecture and devotional atmosphere.', 'October–March', '1 day', 'Budget', NULL, 'image/janaki mandir.jpg', '["Marble temple", "Pilgrimage", "Sacred architecture"]', 26.7287, 85.9094, NULL),
(10, 'Dhanushadham', 'Madhesh Province', NULL, 'Spiritual', 'A revered shrine reputed to contain a piece of Lord Rama’s bow, surrounded by local temples and devotional activity.', 'October–March', '1 day', 'Budget', NULL, 'image/dhanushadham.jpg', '["Sacred shrine", "River rituals", "Pilgrimage"]', 26.7979, 85.9452, NULL),
(11, 'Gadhimai', 'Madhesh Province', NULL, 'Culture', 'A traditional religious complex known for its historic temple and vibrant regional festival heritage.', 'October–March', '1 day', 'Budget', NULL, 'image/gadhimai.jpg', '["Temple festival", "Cultural heritage", "Local tradition"]', 26.9736, 85.1762, NULL),
(12, 'Chure Region', 'Madhesh Province', NULL, 'Nature', 'A region of low hills and forests that forms the southern highland belt of Nepal, offering rural scenery and forest walks.', 'October–March', '2–3 days', 'Budget', NULL, 'image/chure region.jpg', '["Hills", "Forest landscapes", "Rural trails"]', NULL, NULL, NULL),
(13, 'Parsa National Park', 'Madhesh Province', NULL, 'Wildlife', 'A protected lowland park home to rhinos, tigers, elephants, and lush grassland wildlife areas.', 'October–March', '2–3 days', 'Mid-range', NULL, 'image/parsa national park.jpg', '["Wildlife safari", "Rhinos", "Grassland habitat"]', 27.128, 84.995, NULL),
(14, 'Birgunj', 'Madhesh Province', NULL, 'Culture', 'A bustling border city with vibrant markets, food culture, and an important gateway role in southern Nepal.', 'November–February', '1–2 days', 'Budget', NULL, 'image/birgunj.jpg', '["Markets", "Food culture", "Border gateway"]', 27.003, 84.8642, NULL),
(15, 'Kathmandu', 'Bagmati Province', NULL, 'Culture', 'Nepal’s capital offers historic temples, heritage squares, bustling streets, and a rich cultural tapestry.', 'September–November', '3–5 days', 'Mid-range', NULL, 'image/kathmandu.jpg', '["Durbar Square", "Temples", "Thamel markets"]', 27.7172, 85.324, NULL),
(16, 'Patan / Lalitpur', 'Bagmati Province', NULL, 'Culture', 'A historic city celebrated for its art, metalwork, and elegant palace courtyards in the Kathmandu Valley.', 'October–December', '1–2 days', 'Mid-range', NULL, 'image/patan lalitpur.jpg', '["Artisan courtyards", "Metalwork", "Historic temples"]', 27.6722, 85.323, NULL),
(17, 'Bhaktapur', 'Bagmati Province', NULL, 'Culture', 'A preserved medieval city known for traditional Newar architecture, pottery, and quiet heritage lanes.', 'March–May', '1–2 days', 'Mid-range', NULL, 'image/bhaktapur.jpg', '["Pottery squares", "Durbar Square", "Newar culture"]', 27.6714, 85.4295, NULL),
(18, 'Nagarkot', 'Bagmati Province', NULL, 'Relaxation', 'A hill station renowned for dramatic sunrise views over the Himalayas and peaceful mountain lodges.', 'September–December', '1–2 days', 'Mid-range', NULL, 'image/nagarkot.jpg', '["Sunrise viewpoint", "Mountain panorama", "Hillside calm"]', 27.689, 85.4385, NULL),
(19, 'Chitwan National Park', 'Bagmati Province', NULL, 'Wildlife', 'A celebrated wildlife park offering jungle safaris, river experiences, and rare species viewing.', 'October–March', '2–3 days', 'Mid-range', NULL, 'image/chitwan national park.jpg', '["Jungle safari", "Rhinos", "Birdwatching"]', 27.5298, 84.3542, NULL),
(20, 'Langtang Valley', 'Bagmati Province', NULL, 'Adventure', 'A scenic Himalayan valley trek with forested slopes, traditional villages, and mountain views.', 'March–May or September–November', '7–10 days', 'Mid-range', NULL, 'image/langtang valley.jpg', '["Trekking", "Tamang villages", "Alpine scenery"]', 28.2167, 85.5333, NULL),
(21, 'Gosaikunda', 'Bagmati Province', NULL, 'Spiritual', 'A sacred alpine lake destination reached by hiking through highland trails and mountain temples.', 'September–November', '3–4 days', 'Mid-range', NULL, 'image/gosaikunda.jpg', '["Sacred lake", "Hiking", "Mountain temples"]', 28.1233, 85.4, NULL),
(22, 'Shivapuri', 'Bagmati Province', NULL, 'Nature', 'A forested hill park close to Kathmandu, ideal for short treks, sunrise walks, and peaceful nature escapes.', 'September–March', '1 day', 'Budget', NULL, 'image/shivapuri.jpg', '["Forest trails", "Cool air", "Mountain views"]', 27.7608, 85.411, NULL),
(23, 'Swayambhunath', 'Bagmati Province', NULL, 'Culture', 'An ancient hilltop stupa overlooking Kathmandu Valley, rich in Buddhist iconography and city panoramas.', 'October–March', '1 day', 'Budget', NULL, 'image/swayambhunath.jpg', '["Stupa", "City views", "Buddhist art"]', 27.7149, 85.29, NULL),
(24, 'Boudhanath', 'Bagmati Province', NULL, 'Culture', 'A large Tibetan Buddhist stupa complex remembered for its calm atmosphere and prayer rituals.', 'October–March', '1 day', 'Budget', NULL, 'image/boudhanath.jpg', '["Prayer wheels", "Stupa", "Tibetan culture"]', 27.7215, 85.3625, NULL),
(25, 'Pokhara', 'Gandaki Province', NULL, 'Nature', 'A lakeside city surrounded by mountains, popular for boating, peaceful lakeside stays, and adventure sports.', 'March–May or September–November', '3–5 days', 'Mid-range', NULL, 'image/pokhara.jpg', '["Phewa Lake", "Paragliding", "Sunrise views"]', 28.2096, 83.9856, NULL),
(26, 'Phewa Lake', 'Gandaki Province', NULL, 'Nature', 'A shimmering lake with boating, sunset views, and reflections of the Annapurna range.', 'March–May or September–November', '1–2 days', 'Mid-range', NULL, 'image/phewa lake.jpg', '["Boating", "Mountain reflections", "Lakeside promenade"]', 28.2303, 83.9826, NULL),
(27, 'Sarangkot', 'Gandaki Province', NULL, 'Adventure', 'A viewpoint village known for dramatic sunrises, paragliding launches, and sweeping mountain panoramas.', 'September–May', '1 day', 'Budget', NULL, 'image/sarangkot.jpg', '["Sunrise view", "Paragliding", "Mountain panorama"]', 28.2091, 83.9088, NULL),
(28, 'Annapurna Base Camp', 'Gandaki Province', NULL, 'Adventure', 'A world-renowned high camp trek offering close-up views of the Annapurna massif and glacier landscapes.', 'March–May', '12–14 days', 'Premium', NULL, 'image/annapurna base camp.jpg', '["High trek", "Mountain camp", "Glacier views"]', 28.5953, 83.8203, NULL),
(29, 'Mardi Himal', 'Gandaki Province', NULL, 'Adventure', 'A shorter trek through rhododendron forests to a scenic viewpoint near the Annapurna range.', 'March–May', '5–7 days', 'Mid-range', NULL, 'image/mardi himal.jpg', '["Rhododendron trek", "Mountain viewpoints", "High camp"]', 28.3442, 83.9633, NULL),
(30, 'Manang', 'Gandaki Province', NULL, 'Adventure', 'A remote high valley town on the Annapurna trekking circuit, with alpine village life and mountain views.', 'March–May', '3–5 days', 'Premium', NULL, 'image/manang.jpg', '["High valley", "Trekking hub", "Alpine villages"]', 28.6982, 84.0131, NULL),
(31, 'Mustang', 'Gandaki Province', NULL, 'Culture', 'A trans-Himalayan region of desert valleys, ancient villages, and Tibetan Buddhist culture.', 'March–May or September–November', '5–7 days', 'Premium', NULL, 'image/mustang.jpg', '["Ancient villages", "Monasteries", "Desert landscapes"]', 29.0046, 83.8491, NULL),
(32, 'Muktinath', 'Gandaki Province', NULL, 'Spiritual', 'A sacred high-altitude temple site revered by both Hindus and Buddhists, surrounded by mountain views.', 'March–May', '2–3 days', 'Mid-range', NULL, 'image/muktinath.jpg', '["Temple pilgrimage", "Sacred springs", "Highland scenery"]', 28.6924, 83.889, NULL),
(33, 'Bandipur', 'Gandaki Province', NULL, 'Culture', 'A preserved hill town with charming old streets, mountain views, and a relaxed cultural atmosphere.', 'October–December', '1–2 days', 'Mid-range', NULL, 'image/bandipur.jpg', '["Heritage town", "Panoramic views", "Traditional streets"]', 27.8665, 84.4369, NULL),
(34, 'Lumbini', 'Lumbini Province', NULL, 'Spiritual', 'The birthplace of the Buddha, offering serene gardens, monasteries, and pilgrimage spaces.', 'October–March', '2–3 days', 'Budget', NULL, 'image/lumbini.jpg', '["Sacred gardens", "Monasteries", "Peaceful pilgrimage"]', 27.6792, 83.507, NULL),
(35, 'Tilaurakot', 'Lumbini Province', NULL, 'Culture', 'An archaeological site linked to the early life of Prince Siddhartha, with ancient ruins and heritage trails.', 'October–March', '1 day', 'Budget', NULL, 'image/tilaurakot.jpg', '["Archaeology", "Heritage ruins", "Buddhist history"]', 27.5172, 83.0837, NULL),
(36, 'Tansen', 'Lumbini Province', NULL, 'Culture', 'A historic hill town with classic Newari architecture, old bazaars, and sweeping valley views.', 'October–March', '1–2 days', 'Budget', NULL, 'image/tansen.jpg', '["Heritage alleys", "Hill views", "Local markets"]', 27.9544, 83.5484, NULL),
(37, 'Rani Mahal', 'Lumbini Province', NULL, 'Culture', 'A riverfront palace known as the Palace of Smiles, set on the banks of the Kali Gandaki.', 'October–March', '1 day', 'Budget', NULL, 'image/rani mahal.jpg', '["River palace", "Historic architecture", "Scenic setting"]', 27.9994, 83.462, NULL),
(38, 'Bardiya National Park', 'Lumbini Province', NULL, 'Wildlife', 'A western Terai park popular for tiger and rhino safaris, riverine forests, and natural wilderness.', 'November–March', '2–3 days', 'Mid-range', NULL, 'image/bardiya national park.jpg', '["Tiger safari", "Rhinos", "Birdwatching"]', 28.771, 81.4559, NULL),
(39, 'Banke National Park', 'Lumbini Province', NULL, 'Wildlife', 'A forest reserve in western Nepal with tiger habitat, rivers, and wetland wildlife.', 'November–March', '2–3 days', 'Mid-range', NULL, 'image/banke national park.jpg', '["Wildlife reserve", "Wetlands", "Tiger habitat"]', 28.972, 81.769, NULL),
(40, 'Rara-related western gateway attractions', 'Lumbini Province', NULL, 'Gateway', 'Gateway experiences that support access to Rara Lake, including western travel hubs and lakeside staging areas.', 'April–June', '2–4 days', 'Mid-range', NULL, 'image/rara western gateway.jpg', '["Gateway accommodations", "Regional transport", "Western travel support"]', NULL, NULL, NULL),
(41, 'Rara Lake', 'Karnali Province', NULL, 'Nature', 'Nepal’s largest lake, located in a remote highland wilderness framed by pine forests and mountains.', 'April–June', '3–4 days', 'Premium', NULL, 'image/rara lake.jpg', '["Remote lake", "Pine forests", "Highland scenery"]', 29.4547, 82.1196, NULL),
(42, 'Shey Phoksundo Lake', 'Karnali Province', NULL, 'Nature', 'A remarkable turquoise alpine lake in Dolpa, set within a dramatic and remote mountain valley.', 'March–May', '4–6 days', 'Premium', NULL, 'image/shey phoksundo lake.jpg', '["Turquoise lake", "Dolpa wilderness", "Alpine cliffs"]', 29.0313, 82.1998, NULL),
(43, 'Shey Phoksundo National Park', 'Karnali Province', NULL, 'Wildlife', 'A protected area around Shey Phoksundo, home to alpine forests, rare species, and cultural villages.', 'March–May', '4–6 days', 'Premium', NULL, 'image/shey phoksundo national park.jpg', '["Protected park", "Alpine wildlife", "Cultural villages"]', 29.0314, 82.2223, NULL),
(44, 'Jumla', 'Karnali Province', NULL, 'Nature', 'A highland district known for apple orchards, remote roads, and expansive mountain panoramas.', 'March–May', '2–3 days', 'Mid-range', NULL, 'image/jumla.jpg', '["Apple orchards", "Highland landscapes", "Remote culture"]', 29.2744, 82.19, NULL),
(45, 'Sinja Valley', 'Karnali Province', NULL, 'Culture', 'An historic valley preserving early Nepali heritage, ancient ruins, and traditional village culture.', 'March–May or September–November', '1–2 days', 'Budget', NULL, 'image/sinja valley.jpg', '["Ancient ruins", "Cultural heritage", "Traditional valley life"]', 29.5849, 81.8444, NULL),
(46, 'Khaptad National Park', 'Sudurpashchim Province', NULL, 'Nature', 'A tranquil high plateau park with meadows, forests, and a quiet spiritual atmosphere.', 'March–May', '2–3 days', 'Budget', NULL, 'image/khaptad national park.jpg', '["Meadows", "Hiking", "Peaceful plateau"]', 29.1927, 80.1755, NULL),
(47, 'Api Himal', 'Sudurpashchim Province', NULL, 'Adventure', 'A rugged Himalayan peak region with remote trekking and dramatic high mountain views.', 'April–June', '10–14 days', 'Premium', NULL, 'image/api himal.jpg', '["High peaks", "Remote trekking", "Alpine challenge"]', 30.0111, 80.0517, NULL),
(48, 'Shuklaphanta National Park', 'Sudurpashchim Province', NULL, 'Wildlife', 'A western Terai wildlife reserve known for grasslands, rhinos, and rich birdlife.', 'November–March', '2–3 days', 'Mid-range', NULL, 'image/shuklaphanta national park.png', '["Grassland safari", "Rhinos", "Birdwatching"]', 28.687, 80.2088, NULL),
(49, 'Badimalika', 'Sudurpashchim Province', NULL, 'Spiritual', 'A remote mountain pilgrimage area centered around a revered temple in western Nepal.', 'April–June', '2–3 days', 'Budget', NULL, 'image/badimalika.jpg', '["Temple pilgrimage", "Mountain scenery", "Remote trails"]', 29.8238, 80.7445, NULL),
(50, 'Ghodaghodi Lake', 'Sudurpashchim Province', NULL, 'Nature', 'A peaceful wetlands area famous for lakeside walks, birdlife, and serene lakeshore scenery.', 'October–March', '1–2 days', 'Budget', NULL, 'image/ghodaghodi lake.jpg', '["Wetlands", "Birdwatching", "Calm lake"]', 28.73, 80.5337, NULL);
