<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
ini_set('display_errors', 1);
include_once __DIR__ . '/../Mailer.php';
class Action
{
	private $db;
	private $key = "your-test-encryption-key";


	function encryptData($data)
	{
		$cipher = "aes-256-cbc";
		$ivlen = openssl_cipher_iv_length($cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext = openssl_encrypt($data, $cipher, $this->key, $options = 0, $iv);
		return urlencode(base64_encode($iv . $ciphertext));
	}

	function decryptData($encryptedData)
	{
		try {
			$cipher = "aes-256-cbc";
			$ivlen = openssl_cipher_iv_length($cipher);
			$data = base64_decode(urldecode($encryptedData));
			$iv = substr($data, 0, $ivlen);
			$ciphertext = substr($data, $ivlen);
			return @openssl_decrypt($ciphertext, $cipher, $this->key, $options = 0, $iv);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		if ($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$qry = $this->db->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
			if ($qry->num_rows > 0) {
				foreach ($qry->fetch_array() as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['settings'][$key] = $value;
			}

			return 1;
		}
	}


	function save_venue()
	{
		extract($_POST);
		$data = " venue = '$venue' ";
		$data .= ", address = '$address' ";
		$data .= ", description = '$description' ";
		$data .= ", rate = '$rate' ";
		if (empty($id)) {
			//echo "INSERT INTO arts set ".$data;
			$save = $this->db->query("INSERT INTO venue set " . $data);
			if ($save) {
				$id = $this->db->insert_id;
				$folder = "assets/uploads/venue_" . $id;
				if (is_dir($folder)) {
					$files = scandir($folder);
					foreach ($files as $k => $v) {
						if (!in_array($v, array('.', '..'))) {
							unlink($folder . "/" . $v);
						}
					}
				} else {
					mkdir($folder);
				}
				if (isset($img)) {
					for ($i = 0; $i < count($img); $i++) {
						$img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
						$img[$i] = base64_decode($img[$i]);
						$fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
						$upload = file_put_contents($folder . "/" . $fname, $img[$i]);
					}
				}
			}
		} else {
			$save = $this->db->query("UPDATE venue set " . $data . " where id=" . $id);
			if ($save) {
				$folder = "assets/uploads/venue_" . $id;
				if (is_dir($folder)) {
					$files = scandir($folder);
					foreach ($files as $k => $v) {
						if (!in_array($v, array('.', '..'))) {
							unlink($folder . "/" . $v);
						}
					}
				} else {
					mkdir($folder);
				}

				if (isset($img)) {
					for ($i = 0; $i < count($img); $i++) {
						$img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
						$img[$i] = base64_decode($img[$i]);
						$fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
						$upload = file_put_contents($folder . "/" . $fname, $img[$i]);
					}
				}
			}
		}
		if ($save)
			return 1;
	}
	function delete_venue()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM venue where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_book()
	{
		extract($_POST);
		$data = " venue_id = '$venue_id' ";
		$data .= ", name = '$name' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", datetime = '$schedule' ";
		$data .= ", duration = '$duration' ";
		if (isset($status))
			$data .= ", status = '$status' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO venue_booking set " . $data);
		} else {
			if ($status == 1) {
				$venue = $this->db->query("SELECT * FROM venue WHERE id = $venue_id")->fetch_array();

				$testData = json_encode(['bookid' => $id, 'email' => $email, 'venuid' => $venue_id]);

				$param = $this->encryptData($testData);

				$link = "http://localhost/eventms/?rate=" . $param;

				$subject = "Venue Booking Confirmation";

				$body = "
				<html>
				<head>
					<style>
						body {
							font-family: Arial, sans-serif;
							background-color: #f4f7fa;
							margin: 0;
							padding: 0;
						}
						.container {
							background: #ffffff;
							padding: 30px;
							border-radius: 8px;
							box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
							max-width: 600px;
							width: 100%;
							margin: 0 auto;
						}
						h1 {
							text-align: center;
							color: #333;
						}
						p {
							color: #555;
							line-height: 1.6;
						}
						.link-container {
							margin-top: 20px;
							text-align: center;
						}
						.link-container a {
							color: #007bff;
							font-weight: bold;
							text-decoration: none;
						}
						.link-container a:hover {
							text-decoration: underline;
						}
						.footer {
							text-align: center;
							margin-top: 30px;
							color: #aaa;
						}
					</style>
				</head>
				<body>
					<div class='container'>
						<h1>Venue Booking Confirmation</h1>
						<p>Hello <strong>$name</strong>,</p>
						<p>Your booking for <strong>" . $venue['venue'] . "</strong> has been successfully confirmed!</p>
						<p>Please click the link below to rate the venue:</p>
						<div class='link-container'>
							<a href='$link'>Rate Venue</a>
						</div>
						<div class='footer'>
							<p>&copy; 2024 Event Management System</p>
						</div>
					</div>
				</body>
				</html>";

				// Use your mailer to send the email
				$mailer = new Mailer();
				$send = $mailer->sendBasicMail($email, $name, $subject, $body);
			}
			$save = $this->db->query("UPDATE venue_booking set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_book()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM venue_booking where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_register()
	{
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", name = '$name' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		if (isset($status))
			$data .= ", status = '$status' ";
		if (isset($payment_status))
			$data .= ", payment_status = '$payment_status' ";
		else
			$data .= ", payment_status = '0' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO audience set " . $data);
		} else {
			$save = $this->db->query("UPDATE audience set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_register()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM audience where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_event()
	{
		extract($_POST);
		$data = " event = '$event' ";
		$data .= ",venue_id = '$venue_id' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", audience_capacity = '$audience_capacity' ";
		if (isset($payment_status))
			$data .= ", payment_type = '$payment_status' ";
		else
			$data .= ", payment_type = '2' ";
		if (isset($type))
			$data .= ", type = '$type' ";
		else
			$data .= ", type = '1' ";
		$data .= ", amount = '$amount' ";
		$data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";
		if ($_FILES['banner']['tmp_name'] != '') {
			$_FILES['banner']['name'] = str_replace(array("(", ")", " "), '', $_FILES['banner']['name']);
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['banner']['name'];
			$move = move_uploaded_file($_FILES['banner']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", banner = '$fname' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO events set " . $data);
			if ($save) {
				$id = $this->db->insert_id;
				$folder = "assets/uploads/event_" . $id;
				if (is_dir($folder)) {
					$files = scandir($folder);
					foreach ($files as $k => $v) {
						if (!in_array($v, array('.', '..'))) {
							unlink($folder . "/" . $v);
						}
					}
				} else {
					mkdir($folder);
				}
				if (isset($img)) {
					for ($i = 0; $i < count($img); $i++) {
						$img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
						$img[$i] = base64_decode($img[$i]);
						$fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
						$upload = file_put_contents($folder . "/" . $fname, $img[$i]);
					}
				}
			}
		} else {
			$save = $this->db->query("UPDATE events set " . $data . " where id=" . $id);
			if ($save) {
				$folder = "assets/uploads/event_" . $id;
				if (is_dir($folder)) {
					$files = scandir($folder);
					foreach ($files as $k => $v) {
						if (!in_array($v, array('.', '..'))) {
							unlink($folder . "/" . $v);
						}
					}
				} else {
					mkdir($folder);
				}

				if (isset($img)) {
					for ($i = 0; $i < count($img); $i++) {
						$img[$i] = str_replace('data:image/jpeg;base64,', '', $img[$i]);
						$img[$i] = base64_decode($img[$i]);
						$fname = $id . "_" . strtotime(date('Y-m-d H:i')) . "_" . $imgName[$i];
						$upload = file_put_contents($folder . "/" . $fname, $img[$i]);
					}
				}
			}
		}
		if ($save)
			return 1;
	}
	function delete_event()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function get_audience_report()
	{
		extract($_POST);
		$data = array();
		$event = $this->db->query("SELECT e.*,v.venue FROM events e inner join venue v on v.id = e.venue_id  where e.id = $event_id")->fetch_array();
		foreach ($event as $k => $v) {
			if (!is_numeric($k))
				$data['event'][$k] = $v;
		}
		$audience = $this->db->query("SELECT * FROM audience where status = 1 and event_id = $event_id");
		if ($audience->num_rows > 0):
			while ($row = $audience->fetch_assoc()) {
				$row['pstatus'] = $data['event']['payment_type'] == 1 ? "N/A" : ($row['status'] == 1 ? "Paid" : 'Unpaid');
				$data['data'][] = $row;
			}
		endif;
		return json_encode($data);
	}
	function get_venue_report()
	{
		extract($_POST);
		$data = array();
		$date = $month . '-01';
		$venue = $this->db->query("SELECT * FROM venue where id = $venue_id")->fetch_array();
		foreach ($venue as $k => $v) {
			if (!is_numeric($k))
				$data['venue'][$k] = $v;
		}
		$data['venue']['month'] = date("F, d", strtotime($date));
		// echo "SELECT * FROM event where date_format(schedule,'%Y-%m') = '$month' and venue = $venue_id";
		$event = $this->db->query("SELECT * FROM events where date_format(schedule,'%Y-%m') = '$month' and id = $venue_id");
		if ($event->num_rows > 0):
			while ($row = $event->fetch_assoc()) {
				$row['fee'] = $row['payment_type'] == 1 ? "FREE" : number_format($row['amount'], 2);
				$row['etype'] = $row['type'] == 1 ? "Public" : "Private";
				$row['sched'] = date("M d,Y h:i A", strtotime($row['schedule']));
				$data['data'][] = $row;
			}
		endif;
		return json_encode($data);
	}
	function save_art_fs()
	{
		extract($_POST);
		$data = " art_id = '$art_id' ";
		$data .= ", price = '$price' ";
		if (isset($status)) {
			$data .= ", status = '$status' ";
		}


		if (empty($id)) {
			$save = $this->db->query("INSERT INTO arts_fs set " . $data);
		} else {
			$save = $this->db->query("UPDATE arts_fs set " . $data . " where id=" . $id);
		}
		if ($save) {

			return json_encode(array("status" => 1, "id" => $id));
		}
	}
	function delete_art_fs()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM arts_fs where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function delete_order()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM orders where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function update_order()
	{
		extract($_POST);
		$order = $this->db->query("UPDATE orders set status = $status, deliver_schedule = '$deliver_schedule' where id= $order_id ");
		if ($order_id) {
			if (in_array($status, array(1, 3))) {
				$fs = $this->db->query("UPDATE arts_fs set status = 1 where id = $fs_id ");
			} else {
				$fs = $this->db->query("UPDATE arts_fs set status = 0 where id = $fs_id ");
			}
			if ($fs)
				return 1;
		}
	}

	function insertRating($cleanliness, $ambience, $facilities, $services, $bookId, $email, $venueId, $comment)
	{
		$stmt = $this->db->prepare("SELECT name FROM venue_booking WHERE id = ? AND email = ? AND venue_id = ?");
		$stmt->bind_param("iss", $bookId, $email, $venueId);
		$stmt->execute();
		$stmt->bind_result($name);
		$stmt->fetch();
		$stmt->close();

		$name = $name ?? "Anonymous";

		$this->db->begin_transaction();

		try {
			// Insert into venue_rating
			$stmt = $this->db->prepare("INSERT INTO venue_rating (venue_id, booking_id, rater_name, rater_email, comment) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("iisss", $venueId, $bookId, $name, $email, $comment);
			$stmt->execute();
			$ratingId = $stmt->insert_id;
			$stmt->close();

			// Insert into venue_rating_parameters
			$stmt = $this->db->prepare("INSERT INTO venue_rating_parameters (venue_rating_id, cleanliness, service, facilities, ambience) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("idddd", $ratingId, $cleanliness, $services, $facilities, $ambience);
			$stmt->execute();
			$stmt->close();

			// Commit transaction
			$this->db->commit();
		} catch (Exception $e) {
			// Rollback transaction on error
			$this->db->rollback();
			throw $e;
		}
		// OTP validate
	}

	function insertOrUpdateWeight($startDate, $endDate, $weight, $id)
	{
		// 
	}


	function saveRatingWeight()
	{
		extract($_POST);
		$save = 0;

		// Start transaction
		$this->db->begin_transaction();

		try {
			for ($i = 0; $i < count($startRange); $i++) {
				$days_range_start = intval($startRange[$i]);
				$days_range_end = intval($endRange[$i]);
				$weight_value = floatval($weight[$i]);

				// Check for overlapping ranges before inserting
				$stmt = $this->db->prepare("SELECT COUNT(*) FROM rating_weights WHERE (days_range_start <= ? AND days_range_end >= ?) OR (days_range_start <= ? AND days_range_end >= ?)");
				$stmt->bind_param("iiii", $days_range_end, $days_range_start, $days_range_end, $days_range_start);
				$stmt->execute();
				$stmt->bind_result($count);
				$stmt->fetch();
				$stmt->close();

				if ($count > 0) {
					throw new Exception("Overlapping date range detected");
				}

				$stmt = $this->db->prepare("INSERT INTO rating_weights (days_range_start, days_range_end, weight) VALUES (?, ?, ?)");
				if (!$stmt) {
					throw new Exception("Failed to prepare statement: " . $this->db->error);
				}

				$stmt->bind_param("iid", $days_range_start, $days_range_end, $weight_value);
				if (!$stmt->execute()) {
					throw new Exception("Failed to execute statement: " . $stmt->error);
				}
				$stmt->close();
			}

			// Commit transaction
			$this->db->commit();
			$save = 1;
		} catch (Exception $e) {
			// Rollback transaction
			$this->db->rollback();
			error_log($e->getMessage());
			$save = 0;
		}

		return $save;
	}

	function showWeightRate($id)
	{
		/*
		| Algo
		|------------------------------------------------------------
		| Weighted Average = (∑(Rating×Weight)) / ∑(Weight)
		|------------------------------------------------------------
		|
		*/

		// Step 1: Fetch the ratings and review dates
		$query = 'SELECT 
vr.date_created AS review_date,
(vrp.cleanliness + vrp.service + vrp.facilities + vrp.ambience) / 4 AS parameter_average
FROM 
venue v
LEFT JOIN 
venue_rating vr ON v.id = vr.venue_id
LEFT JOIN 
venue_rating_parameters vrp ON vr.id = vrp.venue_rating_id
WHERE 
v.id = ?';

		$stmt = $this->db->prepare($query);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();

		// Initialize an array to store rating data
		$ratings = [];
		while ($row = $result->fetch_assoc()) {
			$ratings[] = [
				'review_date' => $row['review_date'],
				'parameter_average' => $row['parameter_average']
			];
		}

		// Step 2: Fetch the weight based on the review date and store in the array
		foreach ($ratings as &$rating) {
			$review_date = $rating['review_date'];

			// Fetch the weight for the specific review date
			$weightQuery = 'SELECT weight 
		FROM rating_weights 
		WHERE DATEDIFF(NOW(), ?) BETWEEN days_range_start AND days_range_end
		ORDER BY days_range_end DESC 
		LIMIT 1';


			$stmt = $this->db->prepare($weightQuery);
			$stmt->bind_param("s", $review_date);
			$stmt->execute();
			$weightResult = $stmt->get_result();

			// If a weight is found, assign it, otherwise use the default fallback weight
			if ($weightResult->num_rows > 0) {
				$weight = $weightResult->fetch_assoc()['weight'];
			} else {
				// Fallback to the oldest weight if no weight found for the review date
				$fallbackWeightQuery = 'SELECT weight 
					FROM rating_weights 
					ORDER BY days_range_end DESC 
					LIMIT 1';
				$stmt = $this->db->prepare($fallbackWeightQuery);
				$stmt->execute();
				$fallbackResult = $stmt->get_result();
				$weight = $fallbackResult->fetch_assoc()['weight'];
			}

			$rating['weight'] = $weight;
		}

		// Step 3: Calculate the weighted average rating
		$totalWeight = 0;
		$weightedSum = 0;
		foreach ($ratings as $rating) {
			$weightedSum += $rating['parameter_average'] * $rating['weight'];
			$totalWeight += $rating['weight'];
		}

		// Calculate the final weighted average rating
		$finalRating = ($totalWeight > 0) ? ($weightedSum / $totalWeight) : 0;

		// Ensure the rating is within the valid range of 1 to 5
		$test['weighted_average_rating'] = max(1, min(5, $finalRating));

		// Step 4: Return the final rating
		return $test;
	}

	function getWeightedRateOfAll()
	{
		$query = 'SELECT 
					v.id AS venue_id,
					v.venue AS venue_name,
					COALESCE(
						LEAST(
							GREATEST(
								-- Weighted sum of review averages divided by total weight
								SUM(
									(
										(vrp.cleanliness + vrp.service + vrp.facilities + vrp.ambience) / 4
									) * COALESCE(
										rw.weight,
										(SELECT weight FROM rating_weights ORDER BY days_range_end DESC LIMIT 1) -- Default to the oldest weight
									)
								) / NULLIF(
									SUM(
										COALESCE(
											rw.weight,
											(SELECT weight FROM rating_weights ORDER BY days_range_end DESC LIMIT 1)
										)
									), 
									0
								),
								1  -- Ensure the rating is not below 1
							),
							5  -- Ensure the rating does not exceed 5
						), 
						0  -- Default to 0 if no ratings exist
					) AS weighted_average_rating
				FROM 
					venue v
				LEFT JOIN 
					venue_rating vr ON v.id = vr.venue_id
				LEFT JOIN 
					venue_rating_parameters vrp ON vr.id = vrp.venue_rating_id
				LEFT JOIN 
					rating_weights rw ON DATEDIFF(NOW(), vr.date_created) BETWEEN rw.days_range_start AND rw.days_range_end
				GROUP BY 
					v.id, v.venue
				ORDER BY 
					weighted_average_rating DESC;
			';

		$result = $this->db->query($query);
		$data = [];
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
		}
		return $data;
	}
}
