package mainpack;

public class Prime_Counter {
	private int arrLen;
	private int[] userArr = new int[arrLen];
	
	public Prime_Counter(int[] userArr) {
		this.arrLen = userArr.length;
		this.userArr = userArr;
	}
	
	public int checkPrime(int number) {
		for (int counter = 2; counter < number; ++counter) {
			if (number % counter == 0) {
				return 0;
			}
		}
		return 1;
	}
	
	public int count_prime_number() {
		int counter = 0;
		for (int index = 0; index < this.arrLen; ++index) {
			if (checkPrime(userArr[index]) == 1) {
				++counter;
				System.out.println(userArr[index]);
			}
		}
		return counter;
	}
}
