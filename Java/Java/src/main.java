import java.util.Scanner;

public class main {
	public static Scanner userIn = new Scanner(System.in);
	
	public static void main(String[] args) {
		System.out.println("How many number in the array ???");
		int n = userIn.nextInt();
		int[] my_arr = new int[n];
		
		System.out.println("Enter the value please ");
		for (int counter = 0; counter < my_arr.length; ++counter) {
			my_arr[counter] = userIn.nextInt();
		}
		
		Prime_check Count_Prime_Number = new Prime_check(my_arr);
		int number_of_prime = Count_Prime_Number.count_prime_number();
		System.out.println("There are " + number_of_prime + " prime numbers");
	}
}
