
public class Prime_check {
	private int count_prime_number = 0;
	private int arrLen;
	private int[] user_arr = new int[arrLen];
	
	public Prime_check(int[] user_arr) {
		this.user_arr = user_arr;
		this.arrLen = user_arr.length;
	}
	
	public int count_prime_number() {
		for (int index = 0; index < this.arrLen; ++index) {
			for (int counter = 2; counter < this.user_arr[index]; ++counter) {
				if (this.user_arr[index] % counter != 0) {
					++count_prime_number;
				}
			}
		}
		return count_prime_number;
	}
}
