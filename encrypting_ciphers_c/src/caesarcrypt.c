#include <stdio.h>
#include <string.h>

void Encrypt(void) {
	char text[255];
	printf("input text\n");
	scanf("%s", text);
	printf("shiftby\n");
	int shiftby;
	scanf("%d", &shiftby);
	for (int i = 0; i < strlen(text); i++) {
		text[i] += shiftby;
		while (text[i] > 122) text[i] -= 26;
	}
	printf("%s", text);
}
void Decrypt(void) {
	char text[255];
	printf("input coded text\n");
	scanf("%s", text);
	int shiftby;
	printf("shiftby\n");
	scanf("%d", &shiftby);
	for (int i = 0; i < strlen(text); i++) {
		text[i] -= shiftby;
		while (text[i] < 97) text[i] += 26;
	}
	printf("%s", text);
}

int main(void) {
	char eord[3];
	printf("Encrypt or Decrypt?");
	scanf("%s", eord);
	if ((strcmp("e", eord) == 0) || (strcmp("E", eord) == 0)) {
		Encrypt();
		return 0;
	}
	if ((strcmp("d", eord) == 0) || (strcmp("D", eord) == 0)) {
		Decrypt();
		return 0;
	}
	printf("Only letter e or d are allowed");
	return 0;
}

