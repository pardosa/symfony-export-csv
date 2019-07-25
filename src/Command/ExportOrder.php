<?php
// src/Command/ExportOrder.php
namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\Item;
use App\Entity\Product;
use App\Entity\Address;
use App\Entity\Brand;
use App\Entity\Discount;
use App\Entity\Category;

class ExportOrder extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:export-order';
    private $em;
	
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this -> setName('convert')
            -> setDescription('Download JSON file and convert to CSV.')
            -> setHelp('This command allows you to download and convert file...')
            -> addArgument('outputfile', InputArgument::REQUIRED, 'The name of the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->convertJSON($input, $output);
    }

    protected function convertJSON(InputInterface $input, OutputInterface $output)
    {

		// Download JSON File
		$this->getJSONFile();
		
		// JSON to CSV
		$jsonFile = __DIR__ . '\orders.json';
		$this->jsonToCsv($jsonFile, __DIR__ . '/' . $input->getArgument('outputfile'));	
		
    }
	
	private function getJSONFile()
    {
		$headers = array('Accept' => 'application/json');
				
		$response = \Unirest\Request::get('https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl',$headers);
				
		$fs = new Filesystem();
		try {
			$fs->dumpFile(__DIR__ . '/orders.json', $response->raw_body);
		}
		catch(IOException $e) {
			
		}
    }
	
	function jsonToCsv ($jsonFile, $csvFilePath = false) {

		$jsonFile = fopen($jsonFile, 'r');

		$firstLineKeys = false;
		$f = fopen($csvFilePath,'w+');
		while(($json = fgets($jsonFile)) !== false) {
			$array = json_decode($json, true);

			//Exclude order with 0 items ()
			if (count($array["items"] > 0)){
				$order = new Order();
				$order->setOrderId($array["order_id"]);
				$order->setOrderDate($array["order_date"]);
				$order->setShippingPrice($array["shipping_price"]);

				foreach($array["discounts"] as $discount){
					$disc = new Discount();
					$disc->setType($discount["type"]);
					$disc->setValue($discount["value"]);
					$disc->setPriority($discount["priority"]);
					$order->addDiscount($disc);
					$this->em->persist($disc);
				}
				
					$customer = new Customer();
					$customer->setCustomerId($array["customer"]["customer_id"]);
					$customer->setFirstName($array["customer"]["first_name"]);
					$customer->setLastName($array["customer"]["last_name"]);
					$customer->setEmail($array["customer"]["email"]);
					$customer->setPhone($array["customer"]["phone"]);

						$address = new Address();
						$address->setStreet($array["customer"]["shipping_address"]["street"]);
						$address->setPostcode($array["customer"]["shipping_address"]["postcode"]);
						$address->setSuburb($array["customer"]["shipping_address"]["suburb"]);
						$address->setState($array["customer"]["shipping_address"]["state"]);
						$this->em->persist($address);
						
					$customer->setShippingAddress($address);
					$this->em->persist($customer);

				$order->setCustomer($customer);
				
				foreach($array["items"] as $it){
					$item = new Item();
					$item->setQuantity($it["quantity"]);
					$item->setUnitPrice($it["unit_price"]);

						//Check if product exist
						$productRepo = $this->em->getRepository(Product::class);
						$product = $productRepo->find($it["product"]["product_id"]);

						if ($product == NULL){
							$product = new Product();
							$product->setProductId($it["product"]["product_id"]);
							$product->setTitle($it["product"]["title"]);
							$product->setSubtitle($it["product"]["subtitle"]);
							$product->setImage($it["product"]["image"]);
							$product->setThumbnail($it["product"]["thumbnail"]);
							$product->setUrl($it["product"]["url"]);
							$product->setUpc($it["product"]["upc"]);
							$product->setGtin14($it["product"]["gtin14"]);
							$product->setCreatedAt($it["product"]["created_at"]);

								foreach($it["product"]["category"] as $cat){
									$category = new Category();
									$category->setName($cat);
									$product->addCategory($category);
									$this->em->persist($category);
								}

								if ($it["product"]["brand"]["name"] != NULL){
									$brand = new Brand();
									$brand->setName($it["product"]["brand"]["name"]);
									$product->setBrand($brand);
									$this->em->persist($brand);
								}

							$this->em->persist($product);
						}

					$item->setProduct($product);
					$order->addItem($item);
					$this->em->persist($item);
					
				}
				
				$this->em->persist($order);
				$this->em->flush();
				
				$arraycsv = [];
				$arraycsv[] = $order->getOrderId();
				$arraycsv[] = $order->getOrderDate();
				$arraycsv[] = $order->getTotalOrderValue();
				$arraycsv[] = $order->getAvarageUnitPrice();
				$arraycsv[] = $order->countDistinctUnit();
				$arraycsv[] = $order->getTotalUnit();
				$arraycsv[] = $order->getCustomer()->getShippingState();
				
				if (!$firstLineKeys)
				{
					$firstLineKeys = ["order_id", "order_datetime", "total_order_value", "average_unit_price", "distinct_unit_count", "total_units_count", "customer_state"];
					fputcsv($f, $firstLineKeys);
					$firstLineKeys = true; 
				}
				
				fputcsv($f, $arraycsv);
				
			}
			
	 	}
	 	fclose($f);
	}
}